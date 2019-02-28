<?php


namespace Jobleads\Service;


use Jobleads\Model\User;

class UserRatingCalculator
{
    /** @var EventDispatcher */
    private $dispatcher;

    /** @var Translate */
    private $translate;

    /** @var FlashBag */
    private $flashBag;

    public function __construct(EventDispatcher $dispatcher, FlashBag $flashBag, Translate $translate)
    {
        $this->dispatcher = $dispatcher;
        $this->flashBag   = $flashBag;
        $this->translate  = $translate;
    }

    public function increaseUserRating(User $user, int $add = 1)
    {
        $userId        = $user->getId();
        $originalValue = $user->getRating();

        $this->dispatcher->userRatingIncreasing($userId, $originalValue);
        $user->setRating($user->getRating() + $add);
        $this->dispatcher->userRatingIncreased($userId, $user->getRating());
        $this->flashBag->addMessage($this->getTranslatedMessage($originalValue, $user->getRating()));

    }

    public function getTranslatedMessage(int $from, int $to): string
    {
        $message = $this->translate->translate('User rating changed from %s to %s');

        return sprintf($message, $from, $to);
    }
}