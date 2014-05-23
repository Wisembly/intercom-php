<?php

namespace Intercom\Tests;

use \Datetime,
    \PHPUnit_Framework_TestCase;

use GuzzleHttp\Exception\ClientException;

use Intercom\Client,
    Intercom\Request\Request,
    Intercom\Event\Event;

class ClientTest extends PHPUnit_Framework_TestCase
{
    private $appId;
    private $apiKey;

    public function setUp()
    {
        $this->appId  = 'myAppId';
        $this->apiKey = 'myApiKey';
    }

    public function testSend()
    {
        $response      = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $clientRequest = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $request = new Request('POST', Client::INTERCOM_BASE_URL . '/events', [], [
            'event_name' => 'has_been_invited',
            'user_id'    => '2',
            'created'    => '1398246721',
        ]);

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('createRequest')
            ->with(
                'POST',
                Client::INTERCOM_BASE_URL . '/events',
                [
                    'headers' => ['Content-Type' => 'application\json'],
                    'body'    => $request->getBody(),
                    'query'   => $request->getParameters(),
                    'auth'    => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($clientRequest));
        $client->expects(self::once())
            ->method('send')
            ->with($clientRequest)
            ->will(self::returnValue($response));

        (new Client($this->appId, $this->apiKey, $client))->send($request);
    }

    /**
     * @expectedException Intercom\Exception\HttpClientException
     * @expectedMessageException Not Found
     */
    public function testSendWithException()
    {
        $clientRequest = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $request = new Request('POST', Client::INTERCOM_BASE_URL . '/events', [], [
            'event_name' => 'has_been_invited',
            'user_id'    => '2',
            'created'    => '1398246721',
        ]);

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
                'POST',
                Client::INTERCOM_BASE_URL . '/events',
                [
                    'headers' => ['Content-Type' => 'application\json'],
                    'body'    => $request->getBody(),
                    'query'   => $request->getParameters(),
                    'auth'    => [$this->appId, $this->apiKey],
                ]
            )
            ->will(self::returnValue($clientRequest));
        $client->expects(self::once())
            ->method('send')
            ->with($clientRequest)
            ->will(self::throwException($exception));

        (new Client($this->appId, $this->apiKey, $client))->send($request);
    }

    /**
     * @expectedException Intercom\Exception\UserException
     * @expectedMessage An userId or email must be specified and are mandatory to get a User
     */
    public function testGetUserWithoutMandatoryKey()
    {
        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();

        (new Client($this->appId, $this->apiKey, $client))->getUser();
    }

    public function testGetUser()
    {
        $clientRequest = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $request = new Request('GET', Client::INTERCOM_BASE_URL . '/v1/users', ['user_id' => 1, 'email' => 'foo@bar.fr']);

        $response = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $response->expects(self::once())
            ->method('json')
            ->will(self::returnValue([
                'user_id' => 1,
                'email'   => 'foo@bar.fr',
                'token'   => 'fooBarBaz',
            ]));

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('createRequest')
            ->with(
                'GET',
                Client::INTERCOM_BASE_URL . '/v1/users',
                [
                    'headers' => ['Content-Type' => 'application\json'],
                    'body'    => $request->getBody(),
                    'query'   => ['user_id' => 1, 'email' => 'foo@bar.fr'],
                    'auth'    => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($clientRequest));
        $client->expects(self::once())
            ->method('send')
            ->with($clientRequest)
            ->will(self::returnValue($response));

        $user = (new Client($this->appId, $this->apiKey, $client))->getUser(1, 'foo@bar.fr');

        $this->assertInstanceOf('Intercom\Object\User', $user);
        $this->assertEquals(1, $user->getUserId());
        $this->assertEquals('foo@bar.fr', $user->getEmail());
        $this->assertEquals([
            'user_id' => 1,
            'email'   => 'foo@bar.fr',
            'token'   => 'fooBarBaz',
        ], $user->format());
    }
}
