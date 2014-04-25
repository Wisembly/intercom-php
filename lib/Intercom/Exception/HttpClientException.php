<?php

namespace Intercom\Exception;

use \Exception;

/**
 * Occurs when an http call failed
 */
class HttpClientException extends AbstractIntercomException
{
    public function __construct($message = null, $code = null, Exception $previous = null)
    {
        parent::__construct(
            null !== $message ? $message : 'Http client call failed.',
            null !== $code ? $code : null,
            null !== $previous ? $previous : null
        );
    }
}
