<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Fetcher;

use Yutsuku\WordPress\Models\JsonPlaceHolder\AlbumsInterface;
use Yutsuku\WordPress\Models\JsonPlaceHolder\TodosInterface;
use Yutsuku\WordPress\Models\JsonPlaceHolder\PostsInterface;
use Yutsuku\WordPress\Models\JsonPlaceHolder\User;

interface JsonPlaceHolderUserInterface
{
    public function albums(User $user): AlbumsInterface;
    public function todos(User $user): TodosInterface;
    public function posts(User $user): PostsInterface;
}
