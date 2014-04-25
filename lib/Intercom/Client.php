<?php

namespace Intercom;

use GuzzleHttp\ClientInterface as Guzzle,
    GuzzleHttp\Exception\TransferException;

use Intercom\Exception\HttpClientException;

/**
 * Client for Intercom which use HTTPS API
 *
 * @see http://doc.intercom.io/api
 */
class Client
{
    const INTERCOM_BASE_URL = 'https://api.intercom.io';

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
     * Use the curl client to make an http call
     * 
     * @param  IntercomObjectInterface $object An object related with intercom API
     *
     * @throws HttpClientException
     */
    public function send(IntercomObjectInterface $object)
    {
        try {
            $request = $this->client->createRequest(
                $object->getHttpMethod(),
                self::INTERCOM_BASE_URL . $object->getUrl(),
                [
                    'headers' => ['Content-Type' => 'application\json'],
                    'body'    => $object->getParameters(),
                    'auth'    => [$this->appId, $this->apiKey]
                ]
            );

            $this->client->send($request);   
        } catch (TransferException $e) {
            throw new HttpClientException(null, 0, $e);
        }
    }
}
