<?php

namespace Intercom;

use GuzzleHttp\ClientInterface as Guzzle,
    GuzzleHttp\Exception\TransferException;

use Intercom\Exception\HttpClientException,
    Intercom\Request\RequestInterface;

/**
 * Client for Intercom which use HTTPS API
 *
 * @see http://doc.intercom.io/api
 */
abstract class AbstractClient
{
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
     * @param  RequestInterface $request A request related with intercom API
     *
     * @return GuzzleHttp\Message\Response
     *
     * @throws HttpClientException
     */
    public function send(RequestInterface $request)
    {
        try {
            $clientRequest = $this->client->createRequest(
                $request->getMethod(),
                $request->getUrl(),
                [
                    'headers' => ['Content-Type' => 'application\json'],
                    'body'    => $request->getBody(),
                    'query'   => $request->getParameters(),
                    'auth'    => [$this->appId, $this->apiKey]
                ]
            );

            return $this->client->send($clientRequest);
        } catch (TransferException $e) {
            throw new HttpClientException($e->getResponse()->getReasonPhrase(), $e->getResponse()->getStatusCode(), $e);
        }
    }
}
