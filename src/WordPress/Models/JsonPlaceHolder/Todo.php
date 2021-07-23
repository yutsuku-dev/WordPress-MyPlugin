<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

use Yutsuku\WordPress\Models\StringPropertyFromArrayTrait;

class Todo
{
    use StringPropertyFromArrayTrait;

    public int $userId;
    public int $id;
    public string $title;
    public bool $completed;

    public function __construct(?array $args)
    {
        if (is_array($args)) {
            $this->stringPropertiesFromArray($args);

            $this->id = $args['id'];
            $this->userId = $args['userId'];
            $this->completed = (bool) $args['completed'];
        }
    }

    public static function fromArray(array $todo): self
    {
        return new self($todo);
    }
}
