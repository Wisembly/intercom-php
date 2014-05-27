<?php

namespace Intercom\Client;

use Symfony\Component\PropertyAccess\PropertyAccess;

use GuzzleHttp\ClientInterface as Guzzle;

use Intercom\AbstractClient,
    Intercom\Exception\UserException,
    Intercom\Object\User as UserObject,
    Intercom\Request\Search\UserSearch,
    Intercom\Request\Request;

class User extends AbstractClient
{
    const INTERCOM_BASE_URL = 'https://api.intercom.io/v1/users';

    private $accessor;

    /**
     * {@inheritdoc}
     */
    public function __construct($appId, $apiKey, Guzzle $client)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();

        parent::__construct($appId, $apiKey, $client);
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
    public function get($userId = null, $email = null)
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

        $response = $this->send(new Request('GET', self::INTERCOM_BASE_URL, $parameters));

        return $this->hydrateUser($response->json());
    }

    /**
     * Retrieve some User from a search
     *
     * @param  UserSearch $search The search
     *
     * @return User[]
     *
     * @todo The informations about pagination is lost for the moment.
     */
    public function search(UserSearch $search)
    {
        $response = $this->send(new Request('GET', self::INTERCOM_BASE_URL, $search->format()));
        $users = [];

        foreach ($response->json()['users'] as $userData) {
            $users[] = $this->hydrateUser($userData);
        }

        return $users;
    }

    /**
     * Create a User
     *
     * @param  UserObject   $user
     *
     * @throws HttpClientException
     *
     * @return GuzzleHttp\Message\Response
     */
    public function create(UserObject $user)
    {
        return $this->send(new Request('POST', self::INTERCOM_BASE_URL, [], $user->format()));
    }

    /**
     * Update a User
     *
     * @param  UserObject   $user
     *
     * @throws HttpClientException
     *
     * @return GuzzleHttp\Message\Response
     */
    public function update(UserObject $user)
    {
        return $this->send(new Request('PUT', self::INTERCOM_BASE_URL, [], $user->format()));
    }

    /**
     * Delete a User
     *
     * @param  UserObject   $user
     *
     * @throws HttpClientException
     *
     * @return GuzzleHttp\Message\Response
     */
    public function delete(UserObject $user)
    {
        return $this->send(new Request('DELETE', self::INTERCOM_BASE_URL, [], $user->format()));
    }

    /**
     * Hydrate an user with given data
     *
     * @param  array  $data
     *
     * @throws UserException
     *
     * @return UserObject
     */
    private function hydrateUser(array $data)
    {
        /**
         * Here we use the accessor to retrieve user_id and email, because if these keys doesn't exist the accessor
         * will return 'null' and the construct of the User object will throw an Exception.
         */
        $user = new UserObject(
            $this->accessor->getValue($data, '[user_id]'),
            $this->accessor->getValue($data, '[email]')
        );

        foreach ($data as $property => $value) {
            $this->accessor->setValue($user, $property, $value);
        }

        return $user;
    }
}
