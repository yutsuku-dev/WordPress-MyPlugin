<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Fetcher\Cache;

interface TransientInterface
{
    public function store(string $key, array $data, int $expiration);
    public function fetch(string $key): ?array;
    public function delete(string $key);
    public function expiries(): int;
}
