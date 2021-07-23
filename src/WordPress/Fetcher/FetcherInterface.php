<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Fetcher;

use Yutsuku\WordPress\Models\JsonPlaceHolder\User;
use Yutsuku\WordPress\Models\JsonPlaceHolder\Users;

interface FetcherInterface
{
    public function users(): Users;
    public function user(int $id): ?User;
    public function userDetails(User $user): array;
}
