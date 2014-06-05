<?php

namespace Intercom\Object;

use \Datetime;

use Intercom\Request\FormatableInterface,
    Intercom\Client;

/**
 * This class represents an event used by Intercom to add/increment
 * an event associated to a user.
 *
 * @link Doc : http://docs.intercom.io/filtering-users-by-events/Tracking-User-Events-in-Intercom
 * @link Api : http://doc.intercom.io/api/v3/#events
 */
class Event implements FormatableInterface
{
    private $name;
    private $userId;
    private $metadata;
    private $created;

    /**
     * Create an event
     *
     * @param string   $name     The name of the event associated to a user
     * @param string   $userId   The Intercom/app user id
     * @param array    $metadata The metadata associated to the event
     * @param Datetime $created  The created parameter will be converted to Unix timestamp
     */
    public function __construct($name, $userId, $metadata = array(), Datetime $created = null)
    {
        $this->name       = $name;
        $this->userId     = $userId;
        $this->metadata   = $metadata;
        $this->created    = null !== $created ? $created : new Datetime;
    }

    /**
     * {@inheritdoc}
     */
    public function format()
    {
        return [
            'event_name' => (string) $this->getName(),
            'user_id'    => (string) $this->getUserId(),
            'metadata'   => (array)  $this->getMetadata(),
            'created'    => (string) $this->getCreated()->getTimestamp(),
        ];
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get user id
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     * Get metadata
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Get created
     *
     * @return Datetime
     */
    public function getCreated()
    {
        return $this->created;
    }
}
