<?php

declare(strict_types=1);

namespace Yutsuku\WordPress\Api;

use Yutsuku\WordPress\Fetcher\FetcherInterface;

class UsersController extends \WP_REST_Controller
{
    private FetcherInterface $fetcher;

    public function __construct(FetcherInterface $fetcher)
    {
        $this->fetcher = $fetcher;
        $this->namespace = 'Yutsuku/WordPress/MyPlugin/v1';
        $this->rest_base = 'users';
    }

    public function register_routes()
    {
        register_rest_route($this->namespace, $this->rest_base, [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'users'],
                'permission_callback' => '__return_true',
            ],
        ]);
        register_rest_route($this->namespace, $this->rest_base . '/(?P<id>[\d]+)', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'user'],
                'permission_callback' => '__return_true',
            ],
        ]);
    }

    public function users(\WP_REST_Request $request): \WP_REST_Response
    {
        $this->fetcher->fetchAll();
        $data = $this->fetcher->getAll();

        return new \WP_REST_Response($data, 200);
    }

    public function user(\WP_REST_Request $request): \WP_REST_Response
    {
        $id = (int) $request->get_param('id');
        $user = $this->fetcher->fetchById($id);
        $userDetails = $this->fetcher->userDetails($user);

        $data = ['user' => $user, 'details' => $userDetails];

        return new \WP_REST_Response($data, 200);
    }
}
