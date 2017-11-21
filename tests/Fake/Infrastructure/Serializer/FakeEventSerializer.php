<?php

namespace Tests\DddStarterPack\Fake\Infrastructure\Serializer;

use DddStarterPack\Application\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use phpDocumentor\Reflection\Types\Scalar;

class FakeEventSerializer implements Serializer
{
    private $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * Serializes the given data to the specified output format.
     *
     * @param $data
     * @param $format
     * @return string
     */
    public function serialize($data, $format): string
    {
        $serialized = $this->serializer->serialize($data, 'json');

        return $serialized;
    }

    /**
     * Deserializes the given data to the specified type.
     *
     * @param string $data
     * @param string $type
     * @param string $format
     * @return object|array|scalar
     */
    public function deserialize($data, $type, $format)
    {

    }
}
