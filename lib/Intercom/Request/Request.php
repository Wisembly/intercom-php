<?php

namespace Intercom\Request;

/**
 * {@inheritdoc}
 */
class Request implements RequestInterface
{
    private $method;
    private $url;
    private $parameters;
    private $body;

    /**
     * @param string $method     The http method
     * @param string $url        The url
     * @param array  $parameters The parameters (query arguments)
     * @param array  $body       The body
     */
    public function __construct($method, $url, array $parameters = [] , array $body = [])
    {
        $this->method = $method;
        $this->url = $url;
        $this->parameters = $parameters;
        $this->body = $body;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }
}
