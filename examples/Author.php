<?php

/**
 * This is the demo author service.
 */
class Author
{
    private $name = 'name';

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getClass()
    {
        return get_class($this);
    }

    public function getName()
    {
        return $this->name;
    }
}