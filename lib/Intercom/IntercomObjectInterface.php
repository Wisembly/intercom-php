<?php

namespace Intercom;

interface IntercomObjectInterface
{
    /**
     * Retrieve parameters formated for Intercom API parameters
     * 
     * @return array
     */
    public function getParameters();

    /**
     * Get the concerned verb
     * 
     * @return string
     */
    public function getHttpMethod();

    /**
     * Get the absolute url for the concerned API
     * 
     * @return string
     */
    public function getUrl();
}
