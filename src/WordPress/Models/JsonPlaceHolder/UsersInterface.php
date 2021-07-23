<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

interface UsersInterface extends \Iterator
{
    public function add(User $user);
}
