<?php declare(strict_types=1);

namespace SimpleDatatablesBundle\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Survos\SimpleDatatables\Components\ItemGridComponent;
use Survos\SimpleDatatables\Components\SimpleDatatablesComponent;
use Survos\SimpleDatatables\Model\Column;
use function PHPUnit\Framework\assertEquals;

class SimpleDatatablesBundleTest extends TestCase
{
    #[Test]
    public function gridComponent(): void
    {
        $component = new SimpleDatatablesComponent('@survos_simple_datatables_bundle/simple_datatables.html.twig');
        // get from fixture
        $parameters = $component->preMount([
            'data' => [
                ['name' => 'bob', 'age' => 22]
            ]
        ]);

        $component->columns = $parameters['columns'];
//        assertEquals([
//            'name' => new Column('name'),
//            'age' => new Column('age'),
//        ], $component->normalizedColumns());

        $this->assertNotNull($component->columns);


    }

        #[Test]
    public function itemComponent(): void
    {
        $component = new ItemGridComponent();
        assertEquals([], $component->normalizedColumns());
        $data = [
            [
                'name' => 'John Doe',
                'age' => 40,
                'ssn' => '123-45-6789',
            ]
        ];

        $parameters = $component->preMount([
            'caller' => self::class,
            'data' => $data,
            'exclude' => 'ssn'
        ]);
        assertEquals(['name','age'], $parameters['columns']);

        $parameters = $component->preMount([
            'caller' => self::class,
            'data' => $data,
            'columns' => [
                'name',
                [
                    'name' => 'age', // the name of the column
                ]
            ]
        ]);
        $component->columns = $parameters['columns'];
        assertEquals([
            'name' => new Column('name'),
            'age' => new Column('age'),
        ], $component->normalizedColumns());

    }

    #[Test]
    public function columnMethods(): void
    {
        $column = new Column(name: 'age');
        assertEquals('age', $column->name);
        assertEquals('age', $column->title);
        assertEquals('age', (string)$column);
    }

}
