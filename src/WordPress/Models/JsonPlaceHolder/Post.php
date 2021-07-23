<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

use Yutsuku\WordPress\Api\StringPropertyFromArrayTrait;

class Post
{
    use StringPropertyFromArrayTrait;

    public int $userId;
    public int $id;
    public string $title;
    public string $body;

    public function __construct(?array $args)
    {
        if (is_array($args)) {
            $this->stringPropertiesFromArray($args);

            $this->id = $args['id'];
            $this->userId = $args['userId'];
        }
    }

    public static function fromArray(array $post): self
    {
        return new self($post);
    }
}
