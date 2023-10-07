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
use Twig\Environment;

class SurvosSimpleDatatablesBundle extends AbstractBundle
{
    // $config is the bundle Configuration that you usually process in ExtensionInterface::load() but already merged and processed
    /**
     * @param array<mixed> $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
//        dd($config, $config, $builder);
        //        $builder
        //            ->setDefinition('survos.inspection_bundle', new Definition(\Survos\InspectionBundle\Twig\TwigExtension::class))
        //            ->setArgument('$iriConverter', new Reference('api_platform.iri_converter'))
        //            ->addTag('twig.extension')
        //            ->setPublic(false)
        //        ;

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
            ->scalarNode('stimulus_controller')->defaultValue('@survos/grid-bundle/grid')->end()
            ->scalarNode('widthFactor')->defaultValue(2)->end()
            ->scalarNode('height')->defaultValue(30)->end()
            ->scalarNode('foregroundColor')->defaultValue('green')->end()
            ->end();

        ;
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        //        dd($configs);
        //        assert($configs[0]['defaults']['pagination_client_items_per_page'], "pagination_client_items_per_page must be tree in config/api_platform");

        // https://stackoverflow.com/questions/72507212/symfony-6-1-get-another-bundle-configuration-data/72664468#72664468
        //        // iterate in reverse to preserve the original order after prepending the config
        //        foreach (array_reverse($configs) as $config) {
        //            $container->prependExtensionConfig('my_maker', [
        //                'root_namespace' => $config['root_namespace'],
        //            ]);
        //        }
    }
}
