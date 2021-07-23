<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Fetcher;

use \Symfony\Component\HttpClient\Psr18Client;
use \Yutsuku\WordPress\Fetcher\Cache\Transient;
use \Yutsuku\WordPress\Fetcher\Driver\JsonPlaceHolder;
use \Yutsuku\WordPress\Models\JsonPlaceHolder\Users;

class Factory
{
    public static function build()
    {
        return self::BuildJsonPlaceHolder();
    }

    public static function BuildJsonPlaceHolder() : FetcherInterface
    {
        $httpClient = new Psr18Client();
        $requestFactory = new Psr18Client();
        $cache = new Transient();
        $users = new Users();

        return new JsonPlaceHolder($httpClient, $requestFactory, $cache, $users);
    }
}
