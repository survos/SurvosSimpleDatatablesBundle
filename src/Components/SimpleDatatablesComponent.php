<?php

namespace Survos\SimpleDatatables\Components;

use Psr\Log\LoggerInterface;
use Survos\SimpleDatatables\Model\Column;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;
use Twig\Environment;

#[AsTwigComponent('simple_datatables', template: '@SurvosSimpleDatatables/components/grid.html.twig')]
class SimpleDatatablesComponent
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

    public int $perPage=5;

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
            'perPage' => 10,
            'activate' => true,
            'tableId' => null,
            'stimulusController' => '@survos/simple-datatables-bundle/table',
            'search' => true,
            'condition' => true,
            'caller' => null,
            'columns' => [],
        ]);
        $parameters = $resolver->resolve($parameters);
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

}
