<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

class Todo
{
    public int $userId;
    public int $id;
    public string $title;
    public bool $completed;

    public function __construct($args)
    {
        if (is_array($args)) {
            foreach ($args as $key => $value) {
                if (is_string($value)) {
                    $this->{$key} = $value;
                }
            }

            $this->id = $args['id'];
            $this->userId = $args['userId'];
            $this->completed = (bool) $args['completed'];
        }
    }

    public static function fromArray(array $todo)
    {
        return new self($todo);
    }
}
