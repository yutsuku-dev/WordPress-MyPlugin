<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Fetcher\Cache;

interface CacheInterface
{
    public function store($key, $data, $context = null);
    public function fetch($key);
    public function delete($key);
}
