<?php

namespace DddStarterPack\Domain\Event\Subscriber;

use phpDocumentor\Reflection\Types\Scalar;

interface Serializer
{
    /**
     * Serializes the given data to the specified output format.
     *
     * @param $data
     * @param $format
     * @return string
     */
    public function serialize($data, $format): string;

    /**
     * Deserializes the given data to the specified type.
     *
     * @param string $data
     * @param string $type
     * @param string $format
     * @return object|array|scalar
     */
    public function deserialize($data, $type, $format);
}
