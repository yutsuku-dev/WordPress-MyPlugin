<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Yutsuku\WordPress\Fetcher\Driver\JsonPlaceHolder;
use Yutsuku\WordPress\Fetcher\Cache\TransientInterface;
use Yutsuku\WordPress\Models\JsonPlaceHolder\Users;
use Yutsuku\WordPress\Models\JsonPlaceHolder\User;

/**
 * @coversDefaultClass Yutsuku\WordPress\Fetcher\Driver\JsonPlaceHolder
 */
final class JsonPlaceHolderTest extends TestCase
{
    private $driver;
    /** @var PHPUnit\Framework\MockObject\MockObject */
    private $httpClient;
    /** @var PHPUnit\Framework\MockObject\MockObject */
    private $requestFactory;
    /** @var Yutsuku\WordPress\Fetcher\Cache\TransientInterface */
    private $cache;
    /** @var Yutsuku\WordPress\Models\JsonPlaceHolder\UsersInterface */
    private $users;

    public function setUp(): void
    {
        /** @var Psr\Http\Client\ClientInterface */
        $this->httpClient = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var Psr\Http\Message\RequestFactoryInterface */
        $this->requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var Yutsuku\WordPress\Fetcher\Cache\TransientInterface */
        $this->cache = $this->createMock(TransientInterface::class);
        /** @var Yutsuku\WordPress\Models\JsonPlaceHolder\UsersInterface */
        $this->users = new Users();

        $this->driver = new JsonPlaceHolder($this->httpClient, $this->requestFactory, $this->cache, $this->users);
    }

    private function userStubJson(): string
    {
        return '{
            "id": 1,
            "name": "Leanne Graham",
            "username": "Bret",
            "email": "Sincere@april.biz",
            "address": {
                "street": "Kulas Light",
                "suite": "Apt. 556",
                "city": "Gwenborough",
                "zipcode": "92998-3874",
                "geo": {
                    "lat": "-37.3159",
                    "lng": "81.1496"
                }
            },
            "phone": "1-770-736-8031 x56442",
            "website": "hildegard.org",
            "company": {
                "name": "Romaguera-Crona",
                "catchPhrase": "Multi-layered client-server neural-net",
                "bs": "harness real-time e-markets"
            }
        }';
    }

    /**
     * @covers ::users
     */
    public function testUsers(): void
    {

        $response = new Response(200, [], '[
            {
                "id": 1,
                "name": "Leanne Graham",
                "username": "Bret",
                "email": "Sincere@april.biz",
                "address": {
                    "street": "Kulas Light",
                    "suite": "Apt. 556",
                    "city": "Gwenborough",
                    "zipcode": "92998-3874",
                    "geo": {
                        "lat": "-37.3159",
                        "lng": "81.1496"
                    }
                },
                "phone": "1-770-736-8031 x56442",
                "website": "hildegard.org",
                "company": {
                    "name": "Romaguera-Crona",
                    "catchPhrase": "Multi-layered client-server neural-net",
                    "bs": "harness real-time e-markets"
                }
            },
            {
                "id": 2,
                "name": "Ervin Howell",
                "username": "Antonette",
                "email": "Shanna@melissa.tv",
                "address": {
                    "street": "Victor Plains",
                    "suite": "Suite 879",
                    "city": "Wisokyburgh",
                    "zipcode": "90566-7771",
                    "geo": {
                        "lat": "-43.9509",
                        "lng": "-34.4618"
                    }
                },
                "phone": "010-692-6593 x09125",
                "website": "anastasia.net",
                "company": {
                    "name": "Deckow-Crist",
                    "catchPhrase": "Proactive didactic contingency",
                    "bs": "synergize scalable supply-chains"
                }
            }
        ]');
        $response->getBody()->rewind();

        $this->httpClient
            ->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue($response));

        $result = $this->driver->users();

        $this->assertCount(2, $result);
    }

    /**
     * @covers ::users
     */
    public function testEmptyUsers(): void
    {
        $response = new Response(200, [], '[]');
        $response->getBody()->rewind();

        $this->httpClient
            ->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue($response));

        $result =  $this->driver->users();

        $this->assertCount(0, $result);
    }

    /**
     * @covers ::user
     */
    public function testValidUser(): void
    {
        $response = new Response(200, [], $this->userStubJson());
        $response->getBody()->rewind();

        $this->httpClient
            ->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue($response));

        $result = $this->driver->user(1);

        $this->assertInstanceOf(User::class, $result);
    }

    /**
     * @covers ::todos
     */
    public function testTodos(): void
    {
        $user = new User(json_decode($this->userStubJson(), true));

        $response = new Response(200, [], '[
            {
                "userId": 1,
                "id": 1,
                "title": "delectus aut autem",
                "completed": false
            },
            {
                "userId": 1,
                "id": 2,
                "title": "quis ut nam facilis et officia qui",
                "completed": false
            }
        ]');
        $response->getBody()->rewind();

        $this->httpClient
            ->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue($response));

        $result = $this->driver->todos($user);
        $this->assertCount(2, $result);
    }

    /**
     * @covers ::albums
     */
    public function testAlbums(): void
    {
        $user = new User(json_decode($this->userStubJson(), true));

        $response = new Response(200, [], '[
            {
                "userId": 1,
                "id": 1,
                "title": "quidem molestiae enim"
            },
            {
                "userId": 1,
                "id": 2,
                "title": "sunt qui excepturi placeat culpa"
            }
        ]');
        $response->getBody()->rewind();

        $this->httpClient
            ->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue($response));

        $result = $this->driver->albums($user);
        $this->assertCount(2, $result);
    }

    /**
     * @covers ::posts
     */
    public function testPosts(): void
    {
        $user = new User(json_decode($this->userStubJson(), true));

        $response = new Response(200, [], '[
            {
                "userId": 1,
                "id": 1,
                "title": "sunt aut facere repellat provident occaecati excepturi optio reprehenderit",
                "body": "quia et suscipit\nsuscipit recusandae consequuntur expedita et cum\nreprehenderit molestiae ut ut quas totam\nnostrum rerum est autem sunt rem eveniet architecto"
            },
            {
                "userId": 1,
                "id": 2,
                "title": "qui est esse",
                "body": "est rerum tempore vitae\nsequi sint nihil reprehenderit dolor beatae ea dolores neque\nfugiat blanditiis voluptate porro vel nihil molestiae ut reiciendis\nqui aperiam non debitis possimus qui neque nisi nulla"
            }
        ]');
        $response->getBody()->rewind();

        $this->httpClient
            ->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue($response));

        $result = $this->driver->posts($user);
        $this->assertCount(2, $result);
    }
}
