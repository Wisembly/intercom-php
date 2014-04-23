<?php

namespace Intercom\Tests\Exception;

use \PHPUnit_Framework_TestCase;

use Intercom\Exception\HttpClientException;

class HttpClientExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateExceptionWithMessage()
    {
        $exception = new HttpClientException('foo');

        $this->assertEquals('foo', $exception->getMessage());
    }

    public function testCreateExceptionWithoutMessage()
    {
        $exception = new HttpClientException;

        $this->assertEquals('Http client call failed.', $exception->getMessage());
    }
}
