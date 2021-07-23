<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

class Company
{
    public string $name;
    public string $catchPhrase;
    public string $bs;

    public function __construct($args)
    {
        if (is_array($args)) {
            foreach ($args as $key => $value) {
                if (is_string($value)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public static function fromArray(array $company)
    {
        return new self($company);
    }
}
