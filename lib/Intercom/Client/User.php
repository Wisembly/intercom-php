<?php

namespace Intercom\Client;

use GuzzleHttp\ClientInterface as Guzzle;

use Intercom\AbstractClient,
    Intercom\Exception\UserException,
    Intercom\Exception\ConversationException,
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
    
    
    /**
     * Get Users conversations
     *
     * @param  string $userId The userId
     * @param  string $email  The email
     *
     * @throws HttpClientException
     * @throws UserException
     *
     * @return Array
     */
    public function conversations($userId = null, $email = null)
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

        $response = $this->send(new Request('GET', self::INTERCOM_BASE_URL . '/message_threads', $parameters));
        $threadsData = $response->json();

        return $threadsData;
    }

    /**
     * Get a User conversation
     *
     * @param  string $userId The userId
     * @param  string $email  The email
     * @param  string $threadId  The thread ID
     *
     * @throws HttpClientException
     * @throws UserException
     * @throws ConversationException
     *
     * @return Array
     */
    public function conversation($userId = null, $email = null, $threadId = null)
    {
        if (null === $userId && null === $email) {
            throw new UserException('An userId or email must be specified and are mandatory to get a User');
        }

        if (null === $threadId) {
            throw new ConversationException('An thread ID must be specified and are mandatory to get a conversation');
        }

        $parameters = [];

        if (null !== $userId) {
            $parameters['user_id'] = $userId;
        }

        if (null !== $email) {
            $parameters['email'] = $email;
        }

        if (null !== $threadId) {
            $parameters['thread_id'] = $threadId;
        }

        $response = $this->send(new Request('GET', self::INTERCOM_BASE_URL . '/message_threads', $parameters));
        $threadData = $response->json();

        return $threadData;
    }

    /**
     * Mark a conversation as read
     *
     * @param  string $userId The userId
     * @param  string $email  The email
     * @param  string $threadId   The thread
     *
     * @throws HttpClientException
     * @throws UserException
     * @throws ConversationException
     *
     * @return Array
     */
    public function readConversation($userId = null, $email = null, $threadId = null, $read = true, $url = '')
    {
        if (null === $userId && null === $email) {
            throw new UserException('An userId or email must be specified and are mandatory to get a User');
        }

        if (null === $threadId) {
            throw new ConversationException('An thread ID must be specified and are mandatory to get a conversation');
        }

        if (null === $read || empty($read)) {
            throw new ConversationException('Read cannot be empty or null');
        }

        $parameters = [];

        if (null !== $userId) {
            $parameters['user_id'] = $userId;
        }

        if (null !== $email) {
            $parameters['email'] = $email;
        }

        if (null !== $threadId) {
            $parameters['thread_id'] = $threadId;
        }

        if (null !== $read) {
            $parameters['read'] = $read;
        }

        if (null !== $url || !empty($url)) {
            $parameters['url'] = $url;
        }

        $response = $this->send(new Request('PUT', self::INTERCOM_BASE_URL . '/message_threads', $parameters));
        $threadData = $response->json();

        return $threadData;
    }


    /**
     * Reply a conversation
     *
     * @param  string $userId The userId
     * @param  string $email  The email
     * @param  string $threadId   The thread
     * @param  string $body   The body
     *
     * @throws HttpClientException
     * @throws UserException
     * @throws ConversationException
     *
     * @return Array
     */
    public function replyConversation($userId = null, $email = null, $threadId = null, $body = null, $url = '')
    {
        if (null === $userId && null === $email) {
            throw new UserException('An userId or email must be specified and are mandatory to get a User');
        }

        if (null === $threadId) {
            throw new ConversationException('An thread ID must be specified and are mandatory to get a conversation');
        }

        if (null === $body || empty($body)) {
            throw new ConversationException('Message cannot be empty or null');
        }

        $parameters = [];

        if (null !== $userId) {
            $parameters['user_id'] = $userId;
        }

        if (null !== $email) {
            $parameters['email'] = $email;
        }

        if (null !== $threadId) {
            $parameters['thread_id'] = $threadId;
        }

        if (null !== $body) {
            $parameters['body'] = $body;
        }

        if (null !== $url || !empty($url)) {
            $parameters['url'] = $url;
        }

        $parameters['read'] = true;

        $response = $this->send(new Request('PUT', self::INTERCOM_BASE_URL . '/message_threads', $parameters));
        $threadData = $response->json();

        return $threadData;
    }

    /**
     * Create a user conversation
     *
     * @param  string $userId The userId
     * @param  string $email  The email
     * @param  string $body   The body
     *
     * @throws HttpClientException
     * @throws UserException
     * @throws ConversationException
     *
     * @return Array
     */
    public function newConversation($userId = null, $email = null, $body = null, $url = '')
    {
        if (null === $userId && null === $email) {
            throw new UserException('An userId or email must be specified and are mandatory to get a User');
        }

        if (null === $body || empty($body)) {
            throw new ConversationException('Message cannot be empty or null');
        }

        $parameters = [];

        if (null !== $userId) {
            $parameters['user_id'] = $userId;
        }

        if (null !== $email) {
            $parameters['email'] = $email;
        }

        if (null !== $body) {
            $parameters['body'] = $body;
        }

        if (null !== $url || !empty($url)) {
            $parameters['url'] = $url;
        }

        $response = $this->send(new Request('POST', self::INTERCOM_BASE_URL . '/message_threads', $parameters));
        $threadData = $response->json();

        return $threadData;
    }

}
