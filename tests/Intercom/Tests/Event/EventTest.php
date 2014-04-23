<?php

namespace Intercom\Tests\Event;

use \Datetime,
    \PHPUnit_Framework_TestCase;

use Intercom\Event\Event;

class EventTest extends PHPUnit_Framework_TestCase
{
    public function testCreateEvent()
    {
        $created = new Datetime;
        $event   = new Event('event_name', 2);

        $this->assertEquals('POST', $event->getHttpMethod());
        $this->assertEquals('/events', $event->getUrl());
        $this->assertEquals('event_name', $event->getName());
        $this->assertEquals('2', $event->getUserId());
        $this->assertEquals((string) $created->getTimestamp(), $event->getCreated());
        $this->assertEquals([
            'event_name' => 'event_name',
            'user_id'    => '2',
            'created'    => (string) $created->getTimestamp(),
        ], $event->getParameters());
    }
}
