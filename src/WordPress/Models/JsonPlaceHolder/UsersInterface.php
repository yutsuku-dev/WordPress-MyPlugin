<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

Interface UsersInterface extends \Iterator
{
    public function add(User $user);
}
