<?php

namespace DddStarterPack\Application\DataTransformer;

use DddStarterPack\Domain\Model\IdentifiableDomainObject;

abstract class JsonDataTransformer implements DataTransformer
{
    protected $models = [];

    protected $json = [
        'meta' => ['total' => 0],
        'results' => []
    ];

    /**
     * @param IdentifiableDomainObject $domainModel
     * @return DataTransformer
     */
    public function write(IdentifiableDomainObject $domainModel): DataTransformer
    {
        throw new \BadMethodCallException('If you need to transform a single element, use ArrayDataTransformer::write class', 500);
    }

    /**
     * @param \Traversable $domainModelCollection
     * @param int $total
     * @return DataTransformer
     */
    public function writeCollection(\Traversable $domainModelCollection, ?int $total = null): DataTransformer
    {
        $this->json['meta']['total'] = $total ?: count($domainModelCollection);

        return $this;
    }
}
