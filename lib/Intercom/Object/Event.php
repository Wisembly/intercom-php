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
    private $created;
    private $metadata;

    /**
     * Create an event
     *
     * @param string        $name     The name of the event associated to a user
     * @param string        $userId   The Intercom/app user id
     * @param Datetime      $created  The created parameter will be converted to Unix timestamp
     * @param EventMetadata $metadata Optional metadata about the event
     */
    public function __construct($name, $userId, Datetime $created = null, EventMetadata $metadata = null)
    {
        $this->name      = $name;
        $this->userId    = $userId;
        $this->created   = null !== $created ? $created : new Datetime;
        $this->metadata  = $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function format()
    {
        return [
            'event_name' => (string) $this->getName(),
            'user_id'    => (string) $this->getUserId(),
            'created'    => (string) $this->getCreated()->getTimestamp(),
            'metadata'   => null === $this->getMetadata() ? null : $this->getMetadata()->getMetadata(),
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
     * Get created
     *
     * @return Datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get metadata
     * 
     * @return EventMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
