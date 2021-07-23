<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

use Yutsuku\Wordpress\Models\Collection;

class Todos extends Collection implements TodosInterface
{
    public function add(Todo $item)
    {
        $this->elements[] = $item;
    }
}
