<?php


namespace Jobleads\Service;


class FlashBag
{
    private $messages;

    public function __construct()
    {
        $this->messages = [];
    }


    public function addMessage(string $message)
    {
        $this->messages[] = $message;
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}