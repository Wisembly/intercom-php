<?php

namespace Intercom\Client;

use InvalidArgumentException;

use GuzzleHttp\ClientInterface as Guzzle;

use Intercom\AbstractClient,
    Intercom\Exception\UserException,
    Intercom\Exception\BulkException,
    Intercom\Object\User as UserObject,
    Intercom\Request\Search\UserSearch,
    Intercom\Request\PaginatedResponse,
    Intercom\Request\Request;

class User extends AbstractClient
{
    /**
     * Get a User
     *
     * @param  string $userId The userId
     * @param  string $email  The email
     *
     * @throws HttpClientException
     * @throws UserException
     *
     * @return UserObject
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

        $response = $this->send(new Request('GET', self::INTERCOM_BASE_URL . '/users', $parameters));
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
        $response = $this->send(new Request('GET', self::INTERCOM_BASE_URL . '/users', $search->format()))->json();
        $users = [];

        foreach ($response['users'] as $userData) {
            $user = new UserObject(
                $this->accessor->getValue($userData, '[user_id]'),
                $this->accessor->getValue($userData, '[email]')
            );

            $users[] = $this->hydrate($user, $userData);
        }

        return new PaginatedResponse($users, $response['pages']['page'], $response['pages']['next'], $response['pages']['total_pages'], $response['total_count']);
    }

    /**
     * Create a User
     *
     * @param  mixed  $users
     *
     * @throws HttpClientException
     *
     * @return UserObject
     */
    public function createOrUpdate($users)
    {
        if (is_array($users)) {
            return $this->bulk($users);
        }

        if (!$users instanceof UserObject) {
            throw new InvalidArgumentException("UserObject required");
        }

        $response = $this->send(new Request('POST', self::INTERCOM_BASE_URL . '/users', [], $users->format()));

        return $this->hydrate($users, $response->json());
    }

    /**
     * Use bulk to create or update users
     *
     * @param   array   $users
     */
    public function bulk(array $users)
    {
        $userFormated = [];

        array_map(function(UserObject $user) use (&$userFormated) {
            $userFormated[] = $user->format();
        }, $users);

        $response = $this->send(new Request('POST', self::INTERCOM_BASE_URL . '/users/bulk', [], ['users' => $userFormated]));

        if ('200' !== $response->getStatusCode()) {
            throw new BulkException('There is an exception with a bulk');
        }
    }

    /**
     * Delete a User
     *
     * @param  UserObject   $user
     *
     * @throws HttpClientException
     *
     * @return UserObject
     */
    public function delete(UserObject $user)
    {
        $response = $this->send(new Request('DELETE', self::INTERCOM_BASE_URL . '/users', [], $user->format()));

        return $this->hydrate($user, $response->json());
    }
}
