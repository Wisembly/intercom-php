<?php

namespace Intercom\Tests\Object;

use \PHPUnit_Framework_TestCase;

use Intercom\Object\Event,
    Intercom\Object\EventMetadata;
    
class EventTest extends PHPUnit_Framework_TestCase
{
    public function testAddMetadata()
    {
        $metadata = new EventMetadata([
            "invitee_email" => "pi@example.org",
            "invite_code" => "ADDAFRIEND",
        ]);

        $event = new Event('invited-friend', 1, null, $metadata);

        $this->assertEquals([
            "invitee_email" => "pi@example.org",
            "invite_code" => "ADDAFRIEND",
        ], $event->format()['metadata']);
    }
}
