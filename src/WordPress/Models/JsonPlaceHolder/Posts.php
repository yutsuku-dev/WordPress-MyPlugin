<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

use \Yutsuku\Wordpress\Models\Collection;

class Posts extends Collection implements PostsInterface
{
    public function add(Post $post)
    {
        $this->elements[] = $post;
    }
}
