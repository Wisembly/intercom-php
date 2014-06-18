<?php

namespace Intercom\Tests\Object;

use \PHPUnit_Framework_TestCase;

use Intercom\Object\User;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testAddCustomData()
    {
        $user = new User(1, 'FréDériC@bar.fr');
        $user->addCustomData('foo', 'bar');

        $this->assertEquals('bar', $user->getCustomData('foo'));
        $this->assertEquals(['foo' => 'bar'], $user->getCustomData());
        $this->assertEquals(['foo' => 'bar'], $user->getCustomData('zzz'));
        $this->assertEquals('frédéric@bar.fr', $user->getEmail());

        $this->assertTrue($user->hasCustomData('foo'));
        $this->assertFalse($user->hasCustomData('zzz'));
    }
}
