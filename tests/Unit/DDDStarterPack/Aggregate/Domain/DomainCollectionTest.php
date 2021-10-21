<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain;

use DDDStarterPack\Aggregate\Domain\DomainCollection;
use PHPUnit\Framework\TestCase;

class DomainCollectionTest extends TestCase
{
    /** @var DomainCollection<MyData> */
    private DomainCollection $collection;

    protected function setUp(): void
    {
        $a = new MyData();
        $a->data = true;

        $b = new MyData();
        $b->data = true;

        $items = [$a, $b];

        $this->collection = new DomainCollection($items);
    }

    /**
     * @test
     */
    public function it_should_be_iterable(): void
    {
        self::assertCount(2, $this->collection);

        foreach ($this->collection as $key => $item) {
            self::assertSame($key, $this->collection->key());
            self::assertInstanceOf(MyData::class, $item);
        }
    }

    /**
     * @test
     */
    public function it_should_be_arrayable(): void
    {
        $a = new MyData();
        $a->data = true;

        $b = new MyData();
        $b->data = true;

        $expectedCollectionArray = [$a, $b];

        self::assertEquals($expectedCollectionArray, $this->collection->toArray());
    }

    /**
     * @test
     */
    public function it_should_be_mergeable(): void
    {
        $c = new MyData();
        $c->data = true;

        $collection2 = new DomainCollection([$c]);
        $mergedCollection = $this->collection->merge($collection2);

        self::assertCount(3, $mergedCollection);
    }
}
class MyData
{
    public bool $data;
}
