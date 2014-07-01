<?php

namespace Intercom\Tests\Object;

use \PHPUnit_Framework_TestCase;

use Intercom\Object\User;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testAddCustomAttributes()
    {
        $user = new User(1, 'FréDériC@bar.fr');
        $user->addCustomAttributes('foo', 'bar');

        $this->assertEquals('bar', $user->getCustomAttributes('foo'));
        $this->assertEquals(['foo' => 'bar'], $user->getCustomAttributes());
        $this->assertEquals(['foo' => 'bar'], $user->getCustomAttributes('zzz'));
        $this->assertEquals('frédéric@bar.fr', $user->getEmail());

        $this->assertTrue($user->hasCustomAttributes('foo'));
        $this->assertFalse($user->hasCustomAttributes('zzz'));
    }
}
