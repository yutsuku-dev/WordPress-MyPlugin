<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Models\JsonPlaceHolder;

interface AlbumsInterface extends \Iterator
{
    public function add(Album $album);
}
