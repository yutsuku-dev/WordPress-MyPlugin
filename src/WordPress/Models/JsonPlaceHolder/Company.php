<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

use Yutsuku\WordPress\Api\StringPropertyFromArrayTrait;

class Company
{
    use StringPropertyFromArrayTrait;

    public string $name;
    public string $catchPhrase;
    // https://jsonplaceholder.typicode.com/users/1/
    // not our problem
    // phpcs:ignore Inpsyde.CodeQuality.ElementNameMinimalLength.TooShort
    public string $bs;

    public function __construct(?array $args)
    {
        if (is_array($args)) {
            $this->stringPropertiesFromArray($args);
        }
    }

    public static function fromArray(array $company): self
    {
        return new self($company);
    }
}
