<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

use Yutsuku\WordPress\Models\Collection;

class Users extends Collection implements UsersInterface
{
    public function add(User $user)
    {
        $this->elements[] = $user;
    }
}
