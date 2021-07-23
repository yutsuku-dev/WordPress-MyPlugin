<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

use Yutsuku\WordPress\Models\StringPropertyFromArrayTrait;

class User
{
    use StringPropertyFromArrayTrait;

    public int $id;
    public string $name;
    public string $phone;
    public string $website;
    public Company $company;
    public Address $address;

    public function __construct(?array $args)
    {
        if (is_array($args)) {
            $this->stringPropertiesFromArray($args);

            $this->id = $args['id'];
            $this->company = Company::fromArray($args['company']);
            $this->address = Address::fromArray($args['address']);
        }
    }

    public static function fromArray(array $user): self
    {
        return new self($user);
    }
}
