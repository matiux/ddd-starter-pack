<?php

namespace DDDStarterPack\Domain\Model\Message;

/**
 * Questa entitità rappresenta l'ultimo evento pubblicato in coda
 *
 * Class PublishedMessage
 * @package DDDStarterPack\Domain\Model\EventSystem
 */
class BasicPublishedMessage
{
    private $trackerId = null;

    private $exchangeName;

    private $mostRecentPublishedMessageId;

    public function __construct(string $exchangeName, int $aMostRecentPublishedMessageId)
    {
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
}
