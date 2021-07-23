<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Fetcher\Driver;

use \Yutsuku\WordPress\Fetcher\AbstractBase;

use \Psr\Http\Client\ClientInterface;
use \Psr\Http\Client\ClientExceptionInterface;
use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\RequestFactoryInterface;
use Yutsuku\WordPress\Fetcher\Cache\TransientInterface;
use Yutsuku\WordPress\Fetcher\FetcherInterface;
use Yutsuku\WordPress\Fetcher\JsonPlaceHolderUserInterface;
use Yutsuku\WordPress\Models\JsonPlaceHolder\Album;
use Yutsuku\WordPress\Models\JsonPlaceHolder\Albums;
use Yutsuku\WordPress\Models\JsonPlaceHolder\AlbumsInterface;
use Yutsuku\WordPress\Models\JsonPlaceHolder\Post;
use Yutsuku\WordPress\Models\JsonPlaceHolder\Posts;
use Yutsuku\WordPress\Models\JsonPlaceHolder\PostsInterface;
use Yutsuku\WordPress\Models\JsonPlaceHolder\Todo;
use Yutsuku\WordPress\Models\JsonPlaceHolder\Todos;
use Yutsuku\WordPress\Models\JsonPlaceHolder\TodosInterface;
use Yutsuku\WordPress\Models\JsonPlaceHolder\User;
use Yutsuku\WordPress\Models\JsonPlaceHolder\Users;

class JsonPlaceHolder implements FetcherInterface, JsonPlaceHolderUserInterface
{
    private const ENDPOINT = 'https://jsonplaceholder.typicode.com';

    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private TransientInterface $cache;
    private Users $users;
    private bool $cached = true;

    public function __construct(ClientInterface $httpClient, RequestFactoryInterface $requestFactory, TransientInterface $cache, Users $users)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->cache = $cache;
        $this->users = $users;
    }

    public function isCache(): bool
    {
        return $this->cached;
    }

    public function getAll(): Users
    {
        return $this->users;
    }

    protected function fetchWithCache(RequestInterface $request): ?array
    {
        $cache_key = __METHOD__ . $request->getRequestTarget();

        try {
            $data = $this->cache->fetch($cache_key);

            if ($data === false) {
                $response = $this->httpClient->sendRequest($request);
                $data = json_decode($response->getBody()->getContents(), true);

                $this->cached = false;
                $this->cache->store($cache_key, $data, $this->cache->expiries());
            }

            return $data;
        } catch (ClientExceptionInterface $e) {
        }
    }

    public function fetchById(int $id): ?User
    {
        $request = $this->requestFactory->createRequest(
            'GET',
            sprintf('%s/%s/%d', self::ENDPOINT, 'users', $id)
        );

        $data = $this->fetchWithCache($request);

        if ($data) {
            return User::fromArray($data);
        }
    }

    public function fetchAll(): void
    {
        $request = $this->requestFactory->createRequest(
            'GET',
            sprintf('%s/%s', self::ENDPOINT, 'users')
        );
        $data = $this->fetchWithCache($request);

        if ($data) {
            foreach ($data as $entry) {
                $this->users->add(User::fromArray($entry));
            }
        }
    }

    public function albums(User $user): AlbumsInterface
    {
        $collection = new Albums();
        $request = $this->requestFactory->createRequest(
            'GET',
            sprintf('%s/%s/%d/%s', self::ENDPOINT, 'users', $user->id, 'albums')
        );

        $data = $this->fetchWithCache($request);

        if ($data) {
            foreach ($data as $entry) {
                $collection->add(Album::fromArray($entry));
            }
        }

        return $collection;
    }

    public function posts(User $user): PostsInterface
    {
        $collection = new Posts();
        $request = $this->requestFactory->createRequest(
            'GET',
            sprintf('%s/%s/%d/%s', self::ENDPOINT, 'users', $user->id, 'posts')
        );

        $data = $this->fetchWithCache($request);

        if ($data) {
            foreach ($data as $entry) {
                $collection->add(Post::fromArray($entry));
            }
        }

        return $collection;
    }

    public function todos(User $user): TodosInterface
    {
        $collection = new Todos();
        $request = $this->requestFactory->createRequest(
            'GET',
            sprintf('%s/%s/%d/%s', self::ENDPOINT, 'users', $user->id, 'todos')
        );

        $data = $this->fetchWithCache($request);

        if ($data) {
            foreach ($data as $entry) {
                $collection->add(Todo::fromArray($entry));
            }
        }

        return $collection;
    }

    public function userDetails(User $user): array
    {
        $todos = $this->todos($user);
        $posts = $this->posts($user);
        $albums = $this->albums($user);

        return [
            'todos' => $todos,
            'posts' => $posts,
            'albums' => $albums
        ];
    }
}
