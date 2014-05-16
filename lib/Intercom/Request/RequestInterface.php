<?php

namespace Intercom\Request;

/**
 * Represents an Intercom Request
 */
interface RequestInterface
{
    /**
     * Get Http method
     * 
     * @return string
     */
    public function getMethod();

    /**
     * Get the url
     * 
     * @return string
     */
    public function getUrl();

    /**
     * Get the parameters (query arguments)
     * 
     * @return array
     */
    public function getParameters();

    /**
     * Get body
     * 
     * @return array
     */
    public function getBody();
}
