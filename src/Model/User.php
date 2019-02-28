<?php


namespace Jobleads\Model;


class User
{
    /** @var int */
    private $rating;

    /** @var int */
    private $id;

    public function setRating(int $rating)
    {
        $this->rating = $rating;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getId()
    {
        return $this->id;
    }
}