<?php

namespace Intercom\Event;

use \Datetime;

use Intercom\IntercomObjectInterface,
    Intercom\Client;

/**
 * This class represents an event used by Intercom to add/increment
 * an event associated to a user.
 *
 * @see Doc : http://docs.intercom.io/filtering-users-by-events/Tracking-User-Events-in-Intercom
 * @see Api : http://doc.intercom.io/api/v3/#events
 */
class Event implements IntercomObjectInterface
{
    private $name;
    private $userId;
    private $created;

    /**
     * Create an event
     * 
     * @param string   $name    The name of the event associated to a user
     * @param string   $userId  The Intercom/app user id
     * @param Datetime $created The created parameter will be converted to Unix timestamp
     */
    public function __construct($name, $userId, Datetime $created = null)
    {
        $this->name       = $name;
        $this->userId     = $userId;
        $this->created    = null !== $created ? $created->getTimestamp() : time();
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return [
            'event_name' => $this->getName(),
            'user_id'    => $this->getUserId(),
            'created'    => $this->getCreated(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return '/events';
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName()
    {
        return (string) $this->name;
    }

    /**
     * Get user id
     * 
     * @return string
     */
    public function getUserId()
    {
        return (string) $this->userId;
    }

    /**
     * Get created
     * 
     * @return string
     */
    public function getCreated()
    {
        return (string) $this->created;
    }
}
