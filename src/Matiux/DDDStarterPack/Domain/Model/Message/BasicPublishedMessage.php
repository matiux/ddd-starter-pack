<?php

namespace DDDStarterPack\Domain\Model\Message;

/**
 * Questa entitità rappresenta l'ultimo evento pubblicato in coda per un determinato exchangeName
 *
 * Class PublishedMessage
 * @package DDDStarterPack\Domain\Model\EventSystem
 */
abstract class BasicPublishedMessage
{
    protected $trackerId = null;
    protected $exchangeName;
    protected $mostRecentPublishedMessageId;

    public function __construct($trackerId, string $exchangeName, int $aMostRecentPublishedMessageId)
    {
        $this->trackerId = $trackerId;
        $this->exchangeName = $exchangeName;
        $this->mostRecentPublishedMessageId = $aMostRecentPublishedMessageId;
    }

    public function mostRecentPublishedMessageId(): int
    {
        return $this->mostRecentPublishedMessageId;
    }

    /**
     * @param int $maxId
     */
    public function updateMostRecentPublishedMessageId($maxId)
    {
        $this->mostRecentPublishedMessageId = $maxId;
    }

    public function trackerId(): int
    {
        return $this->trackerId;
    }

    public function exchangeName(): string
    {
        return $this->exchangeName;
    }

}
