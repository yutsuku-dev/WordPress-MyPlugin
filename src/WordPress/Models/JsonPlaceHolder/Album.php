<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

use Yutsuku\WordPress\Api\StringPropertyFromArrayTrait;

class Album
{
    use StringPropertyFromArrayTrait;

    public int $userId;
    public int $id;
    public string $title;

    public function __construct(?array $args)
    {
        if (is_array($args)) {
            $this->stringPropertiesFromArray($args);

            $this->userId = $args['userId'];
            $this->id = $args['id'];
        }
    }

    public static function fromArray(array $album): self
    {
        return new self($album);
    }
}
