<?php


namespace Jobleads\Model;


class Book
{
    /** @var string */
    private $title;

    /** @var string */
    private $isbn;

    /** @var string */
    private $author;

    public function __construct(string $title, string $isbn, string $author)
    {
        $this->title = $title;
        $this->isbn = $isbn;
        $this->author = $author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }
}