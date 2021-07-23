<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Fetcher;

abstract class AbstractBase
{
    private array $entries = [];

    public function add(array $entry) : void
    {
        $this->entries[] = $entry;
    }

    public function getAll() : array
    {
        return $this->entries;
    }

    abstract public function fetchAll() : void;
}
