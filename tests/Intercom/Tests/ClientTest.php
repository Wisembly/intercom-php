<?php

namespace Intercom\Tests;

use \Datetime;

use Guzzle\Http\Exception\ClientErrorResponseException;

use Intercom\Client,
    Intercom\Event;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $appId;
    private $apiKey;

    public function setUp()
    {   
        $this->appId  = 'myAppId';
        $this->apiKey = 'myApiKey';
    }

    public function testSendEvent()
    {
        $event  = new Event('has_been_invited_wiz', 2);

        $response = $this->getMock('Guzzle\Http\Message\MessageInterface');

        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');
        $request->expects(self::once())
            ->method('send')
            ->will(self::returnValue($response));

        $client = $this->getMockBuilder('Guzzle\Http\Client')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('setBaseUrl')
            ->with(Client::API_ENDPOINT_EVENTS);
        $client->expects(self::once())
            ->method('post')
            ->with(
                null,
                [
                    'headers' => ['Content-Type' => 'application\json'],
                ],
                [
                    'event_name' => $event->getName(),
                    'user_id' => $event->getUserId(),
                    'created' => $event->getCreated()
                ],
                [
                    'auth' => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($request));

        (new Client($this->appId, $this->apiKey, $client))->sendEvent($event);
    }

    /**
     * @expectedException Intercom\Exception\HttpClientException
     * @expectedMessageException Http client call failed.
     */
    public function testSendEventWithException()
    {
        $event  = new Event('has_been_invited_wiz', 2);

        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');
        $request->expects(self::once())
            ->method('send')
            ->will(self::throwException(new ClientErrorResponseException));

        $client = $this->getMockBuilder('Guzzle\Http\Client')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('setBaseUrl')
            ->with(Client::API_ENDPOINT_EVENTS);
        $client->expects(self::once())
            ->method('post')
            ->with(
                null,
                [
                    'headers' => ['Content-Type' => 'application\json'],
                ],
                [
                    'event_name' => $event->getName(),
                    'user_id' => $event->getUserId(),
                    'created' => $event->getCreated()
                ],
                [
                    'auth' => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($request));

        (new Client($this->appId, $this->apiKey, $client))->sendEvent($event);
    }
}
