<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

Interface PostsInterface extends \Iterator
{
    public function add(Post $post);
}
