<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Service;

/**
 * @template T
 */
interface Serializer
{
    /**
     * Serializes the given data to the specified output format.
     *
     * @param T $data
     * @param $format
     *
     * @return string
     */
    public function serialize($data, string $format): string;

    /**
     * Deserializes the given data to the specified type.
     *
     * @param string $data
     * @param string $type
     * @param string $format
     *
     * @return T
     */
    public function deserialize($data, $type, $format);
}
