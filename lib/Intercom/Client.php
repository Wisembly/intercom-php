<?php

namespace Intercom;

use Guzzle\Http\Client as Guzzle,
    Guzzle\Common\Exception\GuzzleException;

use Intercom\Exception\HttpClientException;

class Client
{
    const API_ENDPOINT_V1 = 'https://api.intercom.io/v1/';
    const API_ENDPOINT_EVENTS = 'https://api.intercom.io/events';

    private $appId;
    private $apiKey;
    private $client;

    /**
     * Initialize an Intercom connection
     * 
     * @param string $appId  Intercom appId
     * @param string $apiKey Intercom apiKey
     * @param Guzzle $client Client of an extern library which handle curl calls
     */
    public function __construct($appId, $apiKey, Guzzle $client)
    {
        $this->appId  = $appId;
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    /**
     * Send an Event
     * 
     * @param  Event  $event Intercom Event
     */
    public function sendEvent(Event $event)
    {
        $this->httpCall($event);
    }

    /**
     * Use the curl client to make an http call
     * 
     * @param  IntercomObjectInterface $object An object related with intercom API
     */
    private function httpCall(IntercomObjectInterface $object)
    {
        if ($object instanceof Event) {
            $this->client->setBaseUrl(self::API_ENDPOINT_EVENTS);
        }

        if ('' === $this->client->getBaseUrl()) {
            $this->client->setBaseUrl(self::API_ENDPOINT_V1);
        }

        try {
            $this->client->post(
                null,
                [
                    'headers' => ['Content-Type' => 'application\json'],
                ],
                [
                    'event_name' => $object->getName(),
                    'user_id' => $object->getUserId(),
                    'created' => $object->getCreated()
                ],
                [
                    'auth' => [$this->appId, $this->apiKey]
                ]
            )->send();   
        } catch (GuzzleException $e) {
            throw new HttpClientException;
        }
    }
}
