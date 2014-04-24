<?php

namespace Intercom\Tests;

use \Datetime,
    \PHPUnit_Framework_TestCase;

use GuzzleHttp\Exception\ClientException;

use Intercom\Client,
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
        $response = $this->getMock('GuzzleHttp\Message\ResponseInterface');
        $request = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $parameters = [
            'event_name' => 'has_been_invited',
            'user_id'    => '2',
            'created'    => '1398246721',
        ];

        $object  = $this->getMockBuilder('Intercom\IntercomObjectInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $object->expects(self::once())
            ->method('getHttpMethod')
            ->will(self::returnValue('POST'));
        $object->expects(self::once())
            ->method('getUrl')
            ->will(self::returnValue('/events'));
        $object->expects(self::once())
            ->method('getParameters')
            ->will(self::returnValue($parameters));

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
                    'body' => $parameters,
                    'auth' => [$this->appId, $this->apiKey]
                ]
            )
            ->will(self::returnValue($request));
        $client->expects(self::once())
            ->method('send')
            ->with($request)
            ->will(self::returnValue($response));

        (new Client($this->appId, $this->apiKey, $client))->send($object);
    }

    /**
     * @expectedException Intercom\Exception\HttpClientException
     * @expectedMessageException Http client call failed.
     */
    public function testSendWithException()
    {
        $request = $this->getMock('GuzzleHttp\Message\RequestInterface');
        $exception = $this->getMock('GuzzleHttp\Exception\TransferException');

        $parameters = [
            'event_name' => 'has_been_invited',
            'user_id'    => '2',
            'created'    => '1398246721',
        ];
            
        $object  = $this->getMockBuilder('Intercom\IntercomObjectInterface')
                        ->disableOriginalConstructor()
                        ->getMock();
        $object->expects(self::once())
            ->method('getHttpMethod')
            ->will(self::returnValue('POST'));
        $object->expects(self::once())
            ->method('getUrl')
            ->will(self::returnValue('/events'));
        $object->expects(self::once())
            ->method('getParameters')
            ->will(self::returnValue($parameters));

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
                    'body' => $parameters,
                    'auth' => [$this->appId, $this->apiKey],
                ]
            )
            ->will(self::returnValue($request));
        $client->expects(self::once())
            ->method('send')
            ->with($request)
            ->will(self::throwException($exception));

        (new Client($this->appId, $this->apiKey, $client))->send($object);
    }
}
