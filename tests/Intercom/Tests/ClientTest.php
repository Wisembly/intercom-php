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
     * @expectedMessageException Not Found
     */
    public function testSendWithException()
    {
        $request = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $parameters = [
            'event_name' => 'has_been_invited',
            'user_id'    => '2',
            'created'    => '1398246721',
        ];

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
