<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Fetcher;

use Yutsuku\WordPress\Models\JsonPlaceHolder\User;
use Yutsuku\WordPress\Models\JsonPlaceHolder\Users;

interface FetcherInterface
{
    public function getAll(): Users;
    public function fetchById(int $id): ?User;
    public function fetchAll(): void;
    public function userDetails(User $user): array;
}
