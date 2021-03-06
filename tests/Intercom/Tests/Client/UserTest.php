<?php

namespace Intercom\Tests\Client;

use \PHPUnit_Framework_TestCase;

use Intercom\Client\User as UserClient,
    Intercom\Object\User,
    Intercom\Request\Search\UserSearch;

/**
 * @coversDefaultClass Intercom\Client\User
 */
class UserTest extends PHPUnit_Framework_TestCase
{
    private $appId;
    private $apiKey;
    private $user;

    public function setUp()
    {
        $this->appId  = 'myAppId';
        $this->apiKey = 'myApiKey';

        $this->user = [
            "intercom_id"        => "52322b3b5d2dd84f23000169",
            "email"              => "ben@intercom.io",
            "user_id"            => "7902",
            "name"               => "Ben McRedmond",
            "created_at"         => 1257553080,
            "last_impression_at" => 1257553080,
            "custom_data"        => [
              "plan" => "pro"
            ],
            "social_profiles"    => [
              [
                "type"     => "twitter",
                "url"      => "http://twitter.com/abc",
                "username" => "abc"
              ],
              [
                "type"     => "facebook",
                "url"      => "http://facebook.com/vanity",
                "username" => "vanity",
                "id"       => "13241141441141413"
              ]
            ],
            "location_data"      => [
              "city_name"      => "Santiago",
              "continent_code" => "SA",
              "country_name"   => "Chile",
              "latitude"       => -33.44999999999999,
              "longitude"      => -70.6667,
              "postal_code"    => "",
              "region_name"    => "12",
              "timezone"       => "Chile/Continental",
              "country_code"   => "CHL"
            ],
            "session_count"            => 0,
            "last_seen_ip"             => "1.2.3.4",
            "last_seen_user_agent"     => "ie6",
            "unsubscribed_from_emails" => false
        ];
    }

    /**
     * @covers ::get()
     * @expectedException Intercom\Exception\UserException
     */
    public function testGetWithNoUserIdAndNoEmail()
    {
        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();

        (new UserClient($this->appId, $this->apiKey, $client))->get();
    }

    /**
     * @covers ::get()
     * @expectedException Intercom\Exception\UserException
     */
    public function testGetWhenTheApiReturnDataWithoutUserIdOrEmail()
    {
        $clientRequest = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $response = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $response->expects(self::once())
            ->method('json')
            ->will(self::returnValue([]));

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('createRequest')
            ->with(
                'GET',
                UserClient::INTERCOM_BASE_URL. '/users',
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'query'   => ['user_id' => 1],
                    'auth'    => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($clientRequest));
        $client->expects(self::once())
            ->method('send')
            ->with($clientRequest)
            ->will(self::returnValue($response));

        $user = (new UserClient($this->appId, $this->apiKey, $client))->get(1);
    }

    /**
     * @covers ::get()
     */
    public function testGet()
    {
        $clientRequest = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $response = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $response->expects(self::once())
            ->method('json')
            ->will(self::returnValue($this->user));

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('createRequest')
            ->with(
                'GET',
                UserClient::INTERCOM_BASE_URL . '/users',
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'query'   => ['user_id' => 7902],
                    'auth'    => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($clientRequest));
        $client->expects(self::once())
            ->method('send')
            ->with($clientRequest)
            ->will(self::returnValue($response));

        $user = (new UserClient($this->appId, $this->apiKey, $client))->get(7902);

        $this->assertInstanceOf('Intercom\Object\User', $user);
        $this->assertEquals('7902', $user->getUserId());
    }

    /**
     * @covers ::get()
     * @expectedException Intercom\Exception\HttpClientException
     * @expectedMessageException Not Found
     */
    public function testWhenUserIsNotFound()
    {
        $clientRequest = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $response = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $response->expects(self::once())
            ->method('getReasonPhrase')
            ->will(self::returnValue('Not Found'));
        $response->expects(self::once())
            ->method('getStatusCode')
            ->will(self::returnValue(404));

        $exception = $this->getMockBuilder('GuzzleHttp\Exception\RequestException')
                          ->disableOriginalConstructor()
                          ->getMock();
        $exception->expects(self::at(0))
            ->method('getResponse')
            ->will(self::returnValue($response));
        $exception->expects(self::at(1))
            ->method('getResponse')
            ->will(self::returnValue($response));

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('createRequest')
            ->with(
                'GET',
                UserClient::INTERCOM_BASE_URL . '/users',
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'query'   => ['user_id' => 2],
                    'auth'    => [$this->appId, $this->apiKey],
                ]
            )
            ->will(self::returnValue($clientRequest));
        $client->expects(self::once())
            ->method('send')
            ->with($clientRequest)
            ->will(self::throwException($exception));

        (new UserClient($this->appId, $this->apiKey, $client))->get(2);
    }

    /**
     * @covers ::search()
     */
    public function testSearch()
    {
        $search = new UserSearch;

        $clientRequest = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $apiResponseData = [
            'users' => [
                $this->user,
                $this->user,
            ],
            'pages' => [
                'page'        => 1,
                'next'        => 2,
                'total_pages' => 10,
            ],
            'total_count' => 100,
        ];

        $response = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $response->expects(self::once())
            ->method('json')
            ->will(self::returnValue($apiResponseData));

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('createRequest')
            ->with(
                'GET',
                UserClient::INTERCOM_BASE_URL . '/users',
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'query'   => $search->format(),
                    'auth'    => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($clientRequest));
        $client->expects(self::once())
            ->method('send')
            ->with($clientRequest)
            ->will(self::returnValue($response));

        $paginatedResponse = (new UserClient($this->appId, $this->apiKey, $client))->search($search);

        $this->assertInstanceOf('Intercom\Request\PaginatedResponse', $paginatedResponse);
        $this->assertInstanceOf('Intercom\Object\User', $paginatedResponse->getContent()[0]);
        $this->assertEquals('7902', $paginatedResponse->getContent()[0]->getUserId());
        $this->assertInstanceOf('Intercom\Object\User', $paginatedResponse->getContent()[1]);
        $this->assertEquals('7902', $paginatedResponse->getContent()[1]->getUserId());
        $this->assertEquals(1, $paginatedResponse->getPage());
        $this->assertEquals(2, $paginatedResponse->getNextPage());
        $this->assertEquals(10, $paginatedResponse->getTotalPages());
        $this->assertEquals(100, $paginatedResponse->getTotalCount());
    }

    /**
     * @covers ::create()
     */
    public function testCreateOrUpdate()
    {
        $user = new User(1);

        $clientRequest = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $response = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $response->expects(self::once())
            ->method('json')
            ->will(self::returnValue($this->user));

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('createRequest')
            ->with(
                'POST',
                UserClient::INTERCOM_BASE_URL . '/users',
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'json'    => $user->format(),
                    'query'   => [],
                    'auth'    => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($clientRequest));
        $client->expects(self::once())
            ->method('send')
            ->with($clientRequest)
            ->will(self::returnValue($response));

        (new UserClient($this->appId, $this->apiKey, $client))->createOrUpdate($user);
    }

    /**
     * @covers ::delete()
     */
    public function testDelete()
    {
        $user = new User(1);

        $clientRequest = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $response = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $response->expects(self::once())
            ->method('json')
            ->will(self::returnValue($this->user));

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('createRequest')
            ->with(
                'DELETE',
                UserClient::INTERCOM_BASE_URL . '/users',
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'json'    => $user->format(),
                    'query'   => [],
                    'auth'    => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($clientRequest));
        $client->expects(self::once())
            ->method('send')
            ->with($clientRequest)
            ->will(self::returnValue($response));

        (new UserClient($this->appId, $this->apiKey, $client))->delete($user);
    }
}
