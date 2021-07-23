<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

interface PostsInterface extends \Iterator
{
    public function add(Post $post);
}
