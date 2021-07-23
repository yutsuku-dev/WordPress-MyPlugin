<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Fetcher\Cache;

interface TransientInterface
{
    public function store(string $key, $data, int $expiration);
    public function fetch(string $key);
    public function delete(string $key);
    public function expiries() : int;
    public function setExpiration(int $value);
}
