<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Fetcher\Cache;

class Transient implements TransientInterface
{
    private int $expiration = 60 * 60; // seconds
    /**
     * @throws TransientException
     */
    public function store(string $key, $data, int $context) : void
    {
        // warn early before WP silent fail
        // https://developer.wordpress.org/reference/functions/set_transient/#more-information
        if (mb_strlen($key) > 172 ) {
            throw new TransientException('Key must be 172 characters or fewer in length');
        }

        set_transient($key, $data, $context);
    }

    public function fetch(string $key)
    {
        return get_transient($key);
    }

    public function delete(string $key) : void
    {
        delete_transient($key);
    }

    public function expiries(): int
    {
        return $this->expiration;
    }

    public function setExpiration(int $value)
    {
        $this->expiration = $value;
    }
}