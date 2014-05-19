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
    private $attributes;

    /**
     * @param array  $attributes Optionals attributes
     */
    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['user_id']) && !isset($attributes['email'])) {
            throw new UserException('An user_id or email attribute must be specified and are mandatory to create a User');
        }

        $this->attributes = $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function format()
    {
        return $this->attributes;
    }

    /**
     * Get userId
     * 
     * @return string
     */
    public function getUserId()
    {
        return isset($this->attributes['user_id']) ? $this->attributes['user_id'] : null;
    }

    /**
     * Get email
     * 
     * @return string
     */
    public function getEmail()
    {
        return isset($this->attributes['email']) ? $this->attributes['email'] : null;
    }
}
