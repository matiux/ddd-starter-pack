<?php

namespace DDDStarterPack\Domain\Service;

interface Serializer
{
    /**
     * Serializes the given data to the specified output format.
     *
     * @param $data
     * @param $format
     * @return string
     */
    public function serialize($data, string $format): string;

    /**
     * Deserializes the given data to the specified type.
     *
     * @param string $data
     * @param string $type
     * @param string $format
     * @return mixed
     */
    public function deserialize($data, $type, $format);
}
