<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Type;

use DDDStarterPack\Type\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /** @var Collection<MyData> */
    private Collection $collection;

    protected function setUp(): void
    {
        $a = new MyData();
        $a->data = true;

        $b = new MyData();
        $b->data = true;

        $items = [$a, $b];

        $this->collection = new Collection($items);
    }

    /**
     * @test
     */
    public function it_should_create_an_empty_collection(): void
    {
        $collection = new Collection();

        self::assertCount(0, $collection);
    }

    /**
     * @test
     */
    public function it_should_create_a_collection_with_a_given_list_of_items(): void
    {
        $collection = new Collection([1, 2, 3]);

        self::assertCount(3, $collection);
    }

    /**
     * @test
     */
    public function it_should_add_an_item_into_the_collection(): void
    {
        /** @var Collection<string> $collection */
        $collection = new Collection();

        self::assertCount(1, $collection->add('some-item'));
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

        $collection2 = new Collection([$c]);
        $mergedCollection = $this->collection->merge($collection2);

        self::assertCount(3, $mergedCollection);
    }

    /**
     * @test
     */
    public function it_should_verify_the_emptiness(): void
    {
        $collection1 = new Collection([1, 2, 3]);
        self::assertFalse($collection1->empty());

        $collection2 = new Collection();
        self::assertTrue($collection2->empty());
    }
}
class MyData
{
    public bool $data;
}
