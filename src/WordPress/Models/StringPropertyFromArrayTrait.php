<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Api;

use Yutsuku\WordPress\Fetcher\FetcherInterface;

trait StringPropertyFromArrayTrait
{
    /**
     * Applies new properties of type `string` to current object.
     * Will match only `string` type in provided `$args` array.
     */
    public function stringPropertiesFromArray(array $args): void
    {
        foreach ($args as $key => $value) {
            if (is_string($value)) {
                $this->{$key} = $value;
            }
        }
    }
}
