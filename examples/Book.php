<?php

/**
 * This is the demo book service.
 * Its depends on author!
 */
class Book
{
    private $author;

    public function __construct(Author $author)
    {
        $this->author = $author;
    }

    public function getClass()
    {
        return get_class($this);
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getAuthorName()
    {
        return $this->author->getName();
    }
}
