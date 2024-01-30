<?php

namespace Survos\SimpleDatatables\Components;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Survos\SimpleDatatables\Model\Column;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('simple_item_grid', template: '@SurvosSimpleDatatables/components/item.html.twig')]
class ItemGridComponent
{
    public function __construct()
    {
    }

    public $data = null;

    public array $columns;

    public ?string $stimulusController = '@survos/grid-bundle/item_grid';

    #[PreMount]
    public function preMount(array $parameters = []): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'data' => null,
            'class' => null,
            'caller' => null,
            'columns' => [],
        ]);
        $parameters = $resolver->resolve($parameters);
        $data = $parameters['data'];
        if (count($parameters['columns']) === 0) {
            if (is_array($data)) {
                $parameters['columns'] = array_keys($data);
            }
        }
        return $parameters;
    }

    /**
     * @return array<string, Column>
     */
    public function normalizedColumns(): iterable
    {
        $normalizedColumns = [];
        foreach ($this->columns as $c) {
            if (is_string($c)) {
                $c = [
                    'name' => $c,
                ];
            }
            assert(is_array($c));
            $column = new Column(...$c);
            $normalizedColumns[$column->name] = $column;
        }
        return $normalizedColumns;
    }
}
