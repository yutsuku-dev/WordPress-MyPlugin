<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

class Geo
{
    public float $lat;
    public float $lng;

    public function __construct(?array $args)
    {
        if (is_array($args)) {
            $this->lat = (float) $args['lat'];
            $this->lng = (float) $args['lng'];
        }
    }

    public static function fromArray(array $geo): self
    {
        return new self($geo);
    }
}
