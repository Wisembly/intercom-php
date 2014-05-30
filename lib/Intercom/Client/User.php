<?php

namespace Intercom\Client;

use GuzzleHttp\ClientInterface as Guzzle;

use Intercom\AbstractClient,
    Intercom\Exception\UserException,
    Intercom\Object\User as UserObject,
    Intercom\Request\Search\UserSearch,
    Intercom\Request\PaginatedResponse,
    Intercom\Request\Request;

class User extends AbstractClient
{
    const INTERCOM_BASE_URL = 'https://api.intercom.io/v1/users';

    /**
     * Get a User
     *
     * @param  string $userId The userId
     * @param  string $email  The email
     *
     * @throws HttpClientException
     * @throws UserException
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
        $userData = $response->json();

        /**
         * Here we use the accessor to retrieve user_id and email, because if these keys doesn't exist the accessor
         * will return 'null' and the construct of the User object will throw an Exception.
         */
        $user = new UserObject(
            $this->accessor->getValue($userData, '[user_id]'),
            $this->accessor->getValue($userData, '[email]')
        );

        return $this->hydrate($user, $userData);
    }

    /**
     * Retrieve some User from a search
     *
     * @param  UserSearch $search The search
     *
     * @return PaginatedResponse
     *
     * @todo The informations about pagination is lost for the moment.
     */
    public function search(UserSearch $search)
    {
        $response = $this->send(new Request('GET', self::INTERCOM_BASE_URL, $search->format()))->json();
        $users = [];

        foreach ($response['users'] as $userData) {
            $user = new UserObject(
                $this->accessor->getValue($userData, '[user_id]'),
                $this->accessor->getValue($userData, '[email]')
            );

            $users[] = $this->hydrate($user, $userData);
        }

        return new PaginatedResponse($users, $response['page'], $response['next_page'], $response['total_pages'], $response['total_count']);
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
        $response = $this->send(new Request('POST', self::INTERCOM_BASE_URL, [], $user->format()));

        return $this->hydrate($user, $response->json());
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
        $response = $this->send(new Request('PUT', self::INTERCOM_BASE_URL, [], $user->format()));

        return $this->hydrate($user, $response->json());
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
        $response = $this->send(new Request('DELETE', self::INTERCOM_BASE_URL, [], $user->format()));

        return $this->hydrate($user, $response->json());
    }
}
