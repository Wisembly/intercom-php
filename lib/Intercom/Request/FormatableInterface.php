<?php

namespace Intercom\Request;

interface FormatableInterface
{
    /**
     * Retrieve attributes formated for Intercom API parameters
     *
     * @return array
     */
    public function format();
}
