<?php


namespace Jobleads\Service;


class EventDispatcher
{
    /** @var callable[] */
    private $listeners;

    public function __construct()
    {
        $this->listeners = [];
    }

    public function addListener(callable $listener)
    {
        $this->listeners[] = $listener;
    }

    public function userRatingIncreasing(int $userId, int $originalValue)
    {
        foreach ($this->listeners as $listener) {
            $listener($userId, $originalValue);
        }
    }

    public function userRatingIncreased(int $userId, int $newValue)
    {
        foreach ($this->listeners as $listener) {
            $listener($userId, $newValue);
        }
    }
}