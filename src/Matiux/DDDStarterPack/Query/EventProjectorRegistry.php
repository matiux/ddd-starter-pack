<?php

declare(strict_types=1);

namespace DDDStarterPack\Query;

use DDDStarterPack\Event\DomainEvent;
use InvalidArgumentException;
use Traversable;

class EventProjectorRegistry
{
    /** @var EventProjector[] */
    protected array $eventProjectors = [];

    /**
     * @param EventProjector[]|Traversable<EventProjector> $eventProjectors
     */
    public function __construct($eventProjectors = [])
    {
        if ($eventProjectors instanceof Traversable) {
            $eventProjectors = iterator_to_array($eventProjectors);
        }

        foreach ($eventProjectors as $eventProjector) {
            $this->addEventProjector($eventProjector);
        }
    }

    public function addEventProjector(EventProjector $eventProjector): void
    {
        if (isset($this->eventProjectors[$eventProjector::class])) {
            throw new InvalidArgumentException(
                sprintf("EventProjector for key '%s' is already set", $eventProjector::class)
            );
        }

        $this->eventProjectors[$eventProjector::class] = $eventProjector;
    }

    public function project(DomainEvent $event): void
    {
        foreach ($this->eventProjectors as $eventProjector) {
            if ($eventProjector->supports($event)) {
                $eventProjector->project($event);

                return;
            }
        }
    }
}
