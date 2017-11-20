<?php

namespace DddStarterPack\Application\EventSystem\Event;

use DddStarterPack\Common\EventSystem\Subscriber\EventSubscriber;

class EventPublisher
{
    /**
     * @var EventSubscriber[]
     */
    private $subscribers;

    private static $instance = null;

    public static function instance(): EventPublisher
    {
        if (null === static::$instance) {

            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->subscribers = [];
    }

    public function __clone()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }

    public function subscribe(EventSubscriber $aDomainEventSubscribe)
    {
        $domainEventSubscribeClass = get_class($aDomainEventSubscribe);

        $found = (bool)array_filter($this->subscribers, function ($v) use ($domainEventSubscribeClass) {

            return get_class($v) === $domainEventSubscribeClass;
        });

        if (!$found) {

            $this->subscribers[] = $aDomainEventSubscribe;
        }

    }

    public function publish(Event $anEvent)
    {
        foreach ($this->subscribers as $aSubscriber) {

            if ($aSubscriber->isSubscribedTo($anEvent)) {

                $aSubscriber->handle($anEvent);
            }
        }
    }
}
