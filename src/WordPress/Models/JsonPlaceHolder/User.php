<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

class User
{
    public int $id;
    public string $name;
    public string $phone;
    public string $website;
    public Company $company;
    public Address $address;

    public function __construct($args)
    {
        if (is_array($args)) {
            foreach($args as $key => $value) {
                if (is_string($value)) {
                    $this->{$key} = $value;
                }
            }

            $this->id = $args['id'];
            $this->company = Company::fromArray($args['company']);
            $this->address = Address::fromArray($args['address']);
        }
    }

    public static function fromArray(array $user)
    {
        return new self($user);
    }
}
