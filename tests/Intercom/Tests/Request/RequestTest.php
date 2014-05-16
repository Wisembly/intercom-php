<?php

namespace Intercom\Tests\Request;

use \PHPUnit_Framework_TestCase;

use Intercom\Request\Request;

class RequestTest extends PHPUnit_Framework_TestCase
{
    public function testCreateRequest()
    {
        $request = new Request('POST', '/users', ['foo' => 'bar'], ['baz' => 'fizz']);

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/users', $request->getUrl());
        $this->assertEquals(['foo' => 'bar'], $request->getParameters());
        $this->assertEquals(['baz' => 'fizz'], $request->getBody());
    }
}
