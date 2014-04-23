<?php

namespace Intercom\Exception;

/**
 * Occurs when an http call failed
 */
class HttpClientException extends AbstractIntercomException
{
    public function __construct($message = null)
    {
        parent::__construct(null !== $message ? $message : 'Http client call failed.');
    }
}
