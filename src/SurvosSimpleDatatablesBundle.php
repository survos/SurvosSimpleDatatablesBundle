<?php

namespace Survos\SimpleDatatables;

use Survos\SimpleDatatables\Components\GridComponent;
use Survos\SimpleDatatables\Components\ItemGridComponent;
use Survos\SimpleDatatables\Twig\TwigExtension;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\UX\StimulusBundle\Twig\StimulusTwigExtension;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Twig\Environment;

class SurvosSimpleDatatablesBundle extends AbstractBundle
{
    // $config is the bundle Configuration that you usually process in ExtensionInterface::load() but already merged and processed
    /**
     * @param array<mixed> $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {

        if (class_exists(Environment::class) && class_exists(StimulusTwigExtension::class)) {
            $builder
                ->setDefinition('survos.simple_datatables_bundle', new Definition(TwigExtension::class))
                ->addTag('twig.extension')
                ->setPublic(false)
            ;
        }

        $builder->register(GridComponent::class)
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->setArgument('$twig', new Reference('twig'))
            ->setArgument('$logger', new Reference('logger'))
            ->setArgument('$stimulusController', $config['stimulus_controller'])
//            ->setArgument('$registry', new Reference('doctrine')) // should be optional
        ;

        $builder->register(ItemGridComponent::class)
            ->setAutowired(true)
            ->setAutoconfigured(true)
        ;
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        // since the configuration is short, we can add it here
        $definition->rootNode()
            ->children()
            ->scalarNode('stimulus_controller')->defaultValue('@survos/simple-datatables-bundle/table')->end()
            ->booleanNode('per_page')->defaultValue(10)->end()
            ->booleanNode('searchable')->defaultValue(true)->end()
            ->scalarNode('fixed_height')->defaultValue(true)->end()
            ->end();

        ;
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if (!$this->isAssetMapperAvailable($builder)) {
            return;
        }

        $dir = realpath(__DIR__.'/../assets/src/controllers');
        assert(file_exists($dir), $dir);

        $builder->prependExtensionConfig('framework', [
            'asset_mapper' => [
                'paths' => [
                    $dir => '@survos/simple-datatables',
                ],
            ],
        ]);

        // https://stackoverflow.com/questions/72507212/symfony-6-1-get-another-bundle-configuration-data/72664468#72664468
        //        // iterate in reverse to preserve the original order after prepending the config
        //        foreach (array_reverse($configs) as $config) {
        //            $container->prependExtensionConfig('my_maker', [
        //                'root_namespace' => $config['root_namespace'],
        //            ]);
        //        }
    }

    private function isAssetMapperAvailable(ContainerBuilder $container): bool
    {
        if (!interface_exists(AssetMapperInterface::class)) {
            assert(false, 'for now, add the asset mapper component');
            return false;
        }

        // check that FrameworkBundle 6.3 or higher is installed
        $bundlesMetadata = $container->getParameter('kernel.bundles_metadata');
        if (!isset($bundlesMetadata['FrameworkBundle'])) {
            assert(false, 'symfony framework 6.3+ required.');
            return false;
        }

        $file = $bundlesMetadata['FrameworkBundle']['path'].'/Resources/config/asset_mapper.php';
        assert(is_file($file), $file);
        return is_file($file);
    }
}
