<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

class Album
{
    public int $userId;
    public int $id;
    public string $title;

    public function __construct($args)
    {
        if (is_array($args)) {
            foreach ($args as $key => $value) {
                if (is_string($value)) {
                    $this->{$key} = $value;
                }
            }

            $this->userId = $args['userId'];
            $this->id = $args['id'];
        }
    }

    public static function fromArray(array $album)
    {
        return new self($album);
    }
}
