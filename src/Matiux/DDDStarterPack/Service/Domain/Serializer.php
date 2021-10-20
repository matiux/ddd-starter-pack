<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Domain;

/**
 * @template T
 */
interface Serializer
{
    /**
     * Serializes the given data to the specified output format.
     *
     * @param T      $data
     * @param string $format
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
    public function deserialize(string $data, string $type, string $format);
}
