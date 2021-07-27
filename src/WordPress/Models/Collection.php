<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models;

abstract class Collection implements \Iterator, \Countable
{
    protected $elements = [];
    protected $position = 0;

    public function __construct()
    {
        $this->position = 0;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->elements[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->elements[$this->position]);
    }

    public function count(): int
    {
        return count($this->elements);
    }
}
