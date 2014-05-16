<?php

namespace Intercom\Object;

interface ObjectInterface
{
    /**
     * Retrieve attributes formated for Intercom API parameters
     * 
     * @return array
     */
    public function format();
}
