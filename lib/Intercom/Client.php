<?php

namespace Intercom;

use GuzzleHttp\ClientInterface as Guzzle,
    GuzzleHttp\Exception\TransferException;

use Intercom\Exception\HttpClientException,
    Intercom\Exception\UserException,
    
    Intercom\Object\User,
    Intercom\Object\Event,

    Intercom\Request\Request,
    Intercom\Request\RequestInterface;

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
     * Create a User
     * 
     * @param  User   $user 
     * 
     * @throws HttpClientException
     *
     * @return GuzzleHttp\Message\Response
     */
    public function createUser(User $user)
    {
        return $this->send(new Request('POST', self::INTERCOM_BASE_URL . '/v1/users', [], $user->format()));
    }

    /**
     * Update a User
     * 
     * @param  User   $user
     * 
     * @throws HttpClientException
     *
     * @return GuzzleHttp\Message\Response
     */
    public function updateUser(User $user)
    {
        return $this->send(new Request('PUT', self::INTERCOM_BASE_URL . '/v1/users', [], $user->format()));
    }

    /**
     * Get a User
     * 
     * @param  string $userId The userId
     * @param  string $email  The email
     *
     * @throws HttpClientException
     * 
     * @return User
     */
    public function getUser($userId = null, $email = null)
    {
        if (null === $userId && null === $email) {
            throw new UserException('An userId or email must be specified and are mandatory to get a User');
        }

        $parameters = [];

        if (null !== $userId) {
            $parameters['user_id'] = $userId;
        }

        if (null !== $email) {
            $parameters['email'] = $email;
        }

        $response   = $this->send(new Request('GET', self::INTERCOM_BASE_URL . '/v1/users', $parameters));
        $attributes = $response->json();

        return new User($attributes);
    }

    /**
     * Create an Event
     * 
     * @param  Event   $event 
     * 
     * @throws HttpClientException
     *
     * @return GuzzleHttp\Message\Response
     */
    public function createEvent(Event $event)
    {
        return $this->send(new Request('POST', self::INTERCOM_BASE_URL . '/events', [], $event->format()));
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
