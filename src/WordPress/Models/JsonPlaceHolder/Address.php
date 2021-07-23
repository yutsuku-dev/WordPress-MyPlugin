<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

use Yutsuku\WordPress\Models\StringPropertyFromArrayTrait;

class Address
{
    use StringPropertyFromArrayTrait;

    public string $street;
    public string $suite;
    public string $city;
    public string $zipcode;
    public Geo $geo;

    public function __construct(?array $args)
    {
        if (is_array($args)) {
            $this->stringPropertiesFromArray($args);

            $this->geo = Geo::fromArray($args['geo']);
        }
    }

    public static function fromArray(array $address): self
    {
        return new self($address);
    }
}
