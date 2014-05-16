<?php

namespace Intercom\Object;

use Intercom\Object\ObjectInterface,
    Intercom\Exception\UserException;

/**
 * This class represents an Intercom User Object
 *
 * @link Api : http://doc.intercom.io/api/#users
 */
class User implements ObjectInterface
{
    private $userId;
    private $email;
    private $attributes;

    /**
     * @param int    $userId     The user's id
     * @param string $email      The user's email
     * @param array  $attributes Optionals attributes
     */
    public function __construct($userId = null, $email = null, array $attributes = [])
    {
        if (null === $userId && null === $email) {
            throw new UserException('An userId or email must be specified and are mandatory to create a User');
        }

        $this->userId     = $userId;
        $this->email      = $email;
        $this->attributes = $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function format()
    {
        $attributes = [];

        if (null !== $this->userId) {
            $attributes['user_id'] = $this->userId;
        }

        if (null !== $this->email) {
            $attributes['email'] = $this->email;
        }

        return array_merge($attributes, $this->attributes);
    }

    /**
     * Get userId
     * 
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get email
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
