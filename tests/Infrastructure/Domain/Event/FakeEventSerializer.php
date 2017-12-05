<?php

namespace Tests\DDDStarterPack\Infrastructure\Domain\Event;

use DDDStarterPack\Domain\Event\Serializer;
use JMS\Serializer\SerializerBuilder;
use phpDocumentor\Reflection\Types\Scalar;

class FakeEventSerializer implements Serializer
{
    /**
     * Serializes the given data to the specified output format.
     *
     * @param $data
     * @param $format
     * @return string
     */
    public function serialize($data, $format): string
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($data, $format);
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
