<?php

namespace Turbo;

class Route
{
    private $route;
    private $get;
    private $post;
    private $put;
    private $delete;

    function __construct($method, $route, $cb)
    {
        $this->route = $route;
        $method = strtolower($method);
        $this->$method = $cb;
    }

    function getRoute()
    {
        return $this->route;
    }

    function getMethod($method)
    {
        $method = strtolower($method);
        return $this->$method;
    }

    function addMethod($method, $cb)
    {
        $method = strtolower($method);
        $this->$method = $cb;
    }
}
