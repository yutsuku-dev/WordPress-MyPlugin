<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

interface TodosInterface extends \Iterator
{
    public function add(Todo $todo);
}
