<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

use Yutsuku\Wordpress\Models\Collection;

class Albums extends Collection implements AlbumsInterface
{
    public function add(Album $album)
    {
        $this->elements[] = $album;
    }
}
