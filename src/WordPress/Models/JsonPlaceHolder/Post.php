<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

class Post
{
    public int $userId;
    public int $id;
    public string $title;
    public string $body;

    public function __construct($args)
    {
        if (is_array($args)) {
            foreach($args as $key => $value) {
                if (is_string($value)) {
                    $this->{$key} = $value;
                }
            }

            $this->id = $args['id'];
            $this->userId = $args['userId'];
        }
    }

    public static function fromArray(array $post)
    {
        return new self($post);
    }
}
