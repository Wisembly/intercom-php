<?php

namespace Intercom\Tests\Object;

use \PHPUnit_Framework_TestCase;

use Intercom\Object\User;

class UserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Intercom\Exception\UserException
     * @expectedMessageException An user_id or email attribute must be specified and are mandatory to create a User
     */
    public function testCreateAnUserWithoutUserIdOrEmail()
    {
        new User(['token'   => 'fooBarBaz']);
    }

    public function testUser()
    {
        $attributes = [
            'user_id' => 1,
            'email'   => 'foo@bar.fr',
            'token'   => 'fooBarBaz',
        ];

        $user = new User($attributes , 'POST');

        $this->assertEquals(1, $user->getUserId());
        $this->assertEquals('foo@bar.fr', $user->getEmail());
        $this->assertEquals([
            'user_id' => 1,
            'email'   => 'foo@bar.fr',
            'token'   => 'fooBarBaz',
        ], $user->format());
    }
}
