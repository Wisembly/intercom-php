<?php

namespace Intercom\Tests\Client;

use \PHPUnit_Framework_TestCase;

use Intercom\Client\Event as EventClient,
    Intercom\Object\Event;

/**
 * @coversDefaultClass Intercom\Client\Event
 */
class EventTest extends PHPUnit_Framework_TestCase
{
    private $appId;
    private $apiKey;

    public function setUp()
    {
        $this->appId  = 'myAppId';
        $this->apiKey = 'myApiKey';
    }

    /**
     * @covers ::create()
     */
    public function testCreate()
    {
        $event = new Event('event_name', 1);

        $clientRequest = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $client->expects(self::once())
            ->method('createRequest')
            ->with(
                'POST',
                EventClient::INTERCOM_BASE_URL . '/events',
                [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'json'    => $event->format(),
                    'query'   => [],
                    'auth'    => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($clientRequest));
        $client->expects(self::once())
            ->method('send')
            ->with($clientRequest);

        (new EventClient($this->appId, $this->apiKey, $client))->create($event);
    }
}
