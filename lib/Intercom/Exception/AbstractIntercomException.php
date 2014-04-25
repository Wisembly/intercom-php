<?php

namespace Intercom\Exception;

use \Exception;

/**
 * The base of all Intercom lib exceptions
 */
abstract class AbstractIntercomException extends Exception implements IntercomExceptionInterface
{
    public function __construct($message = null, $code = null, Exception $previous = null)
    {
        parent::__construct(
            null !== $message ? $message : 'An error occured with Intercom.',
            null !== $code ? $code : null,
            null !== $previous ? $previous : null
        );
    }
}
