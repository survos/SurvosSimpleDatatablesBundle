<?php

namespace Survos\SimpleDatatables\Components;

use Psr\Log\LoggerInterface;
use Survos\SimpleDatatables\Model\Column;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Twig\Environment;

#[AsTwigComponent('grid', template: '@SurvosSimpleDatatables/components/grid.html.twig')]
class GridComponent
{
    public function __construct(
        private Environment $twig,
        private LoggerInterface $logger,
        public ?string $stimulusController,
    )
    {
    }

    public ?iterable $data = null;
    public array $columns;
    public bool $search = true;
    public bool $trans = true;
    public string|bool|null $domain = null;


    public bool $useDatatables = true;
    public bool $info = false;
    public bool $condition = true;
    public string $scrollY = '70vh';
    public string $dom='?';
    public array $searchPanesFields=[];
    public ?string $tableId = null;
    public string $tableClasses = '';

    #[PreMount]
    public function preMount(array $parameters = []): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'data' => null,
            'class' => null,
            'dom' => 'Plfrtip',
            'useDatatables' => true,

            'tableId' => null,
            'tableClasses' => '',
            'scrollY' => '50vh',
//            'stimulusController' => '@survos/grid-bundle/grid',
            'search' => true,
            'info' => false,
            'condition' => true,
            'trans' => false,
            'domain' => null,
            'caller' => null,
            'columns' => [],
        ]);
        $parameters = $resolver->resolve($parameters);
        if (is_null($parameters['data'])) {
            $class = $parameters['class'];
            assert($class, "Must pass class or data");

            // @todo: something clever to limit memory, use yield?
//            $parameters['data'] = $this->registry->getRepository($class)->findAll();
        }
        //        $resolver->setAllowedValues('type', ['success', 'danger']);
        //        $resolver->setRequired('message');
        //        $resolver->setAllowedTypes('message', 'string');
        return $parameters;
    }

    /**
     * @return array<string, Column>
     */
    public function normalizedColumns(): iterable
    {
        $normalizedColumns = [];
        foreach ($this->columns as $c) {
            if (empty($c)) {
                continue;
            }
            if (is_string($c)) {
                $c = [
                    'name' => $c,
                ];
            }
            assert(is_array($c));
            $column = new Column(...$c);
            if ($column->condition) {
                $normalizedColumns[$column->name] = $column;
            }
        }
        return $normalizedColumns;
    }

    public function searchPanesColumns(): int
    {
        $count = 0;
        // count the number, if > 6 we could figured out the best layout
        foreach ($this->normalizedColumns() as $column) {
//            dd($column);
            if ($column->inSearchPane) {
                $count++;
            }
        }
        $count = min($count, 6);
        return $count;
    }
}
