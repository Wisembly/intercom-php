<?php

namespace Intercom;

use Exception;

use Symfony\Component\PropertyAccess\PropertyAccess,
    Symfony\Component\PropertyAccess\Exception\AccessException;

use GuzzleHttp\ClientInterface as Guzzle;

use Intercom\Exception\HttpClientException,
    Intercom\Request\RequestInterface;

/**
 * Client for Intercom which use HTTPS API
 *
 * @see http://doc.intercom.io/api
 */
abstract class AbstractClient
{
    const INTERCOM_BASE_URL = 'https://api.intercom.io';

    private $appId;
    private $apiKey;
    private $client;

    protected $accessor;

    /**
     * Initialize an Intercom connection
     *
     * @param string $appId  Intercom appId
     * @param string $apiKey Intercom apiKey
     * @param Guzzle $client Client of an extern library which handle curl calls
     */
    public function __construct($appId, $apiKey, Guzzle $client)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();

        $this->appId  = $appId;
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    /**
     * Use the curl client to make an http call
     *
     * @param  RequestInterface $request A request related with intercom API
     *
     * @return GuzzleHttp\Message\Response
     *
     * @throws HttpClientException
     */
    public function send(RequestInterface $request)
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'query'   => $request->getParameters(),
            'auth'    => [$this->appId, $this->apiKey]
        ];

        if ('GET' !== $request->getMethod()) {
            $options = array_merge($options, ['json' => $request->getBody()]);
        }

        try {
            $clientRequest = $this->client->createRequest(
                $request->getMethod(),
                $request->getUrl(),
                $options
            );

            return $this->client->send($clientRequest);

        // Catch all Guzzle\Request exceptions
        } catch (Exception $e) {
            throw new HttpClientException($e->getResponse()->getReasonPhrase(), $e->getResponse()->getStatusCode(), $e);
        }
    }

    /**
     * Hydrate an object
     *
     * @param  object $object
     * @param  array  $data
     *
     * @return object
     */
    protected function hydrate($object, array $data)
    {
        foreach ($data as $property => $value) {
            try {
                $this->accessor->setValue($object, $property, $value);
            } catch (AccessException $e) {
                /**
                 * For the moment the properties for Intercom object like User or Event change sometimes.
                 *
                 * If you want use a unimplemented property, contribute on https://github.com/Wisembly/intercom-php
                 */
            }
        }

        return $object;
    }
}
