<?php

namespace Intercom\Tests\Object;

use \PHPUnit_Framework_TestCase;

use Intercom\Object\User;

class UserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Intercom\Exception\UserException
     * @expectedMessageException An userId or email must be specified and are mandatory to create a User
     */
    public function testCreateAnUserWithoutUserIdOrEmail()
    {
        new User;
    }

    public function testUser()
    {
        $user = new User(1, 'foo@bar.fr', ['token' => 'fooBarBaz'], 'POST');

        $this->assertEquals(1, $user->getUserId());
        $this->assertEquals('foo@bar.fr', $user->getEmail());
        $this->assertEquals([
            'user_id' => 1,
            'email'   => 'foo@bar.fr',
            'token'   => 'fooBarBaz',
        ], $user->format());
    }
}
