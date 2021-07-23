<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

class Address
{
    public string $street;
    public string $suite;
    public string $city;
    public string $zipcode;
    public Geo $geo;

    public function __construct($args)
    {
        if (is_array($args)) {
            foreach($args as $key => $value) {
                if (is_string($value)) {
                    $this->{$key} = $value;
                }
            }
            $this->geo = Geo::fromArray($args['geo']);
        }
    }

    public static function fromArray(array $address)
    {
        return new self($address);
    }
}
