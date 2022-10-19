<?php

namespace Andresser17\Turbo;

use \Turbo\Route;
use \Turbo\Request;
use \Turbo\Response;

class Router
{
    public $method;
    public $resources;
    public $routes;

    function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->resources = $_SERVER["PATH_INFO"];
        $this->routes = [];
    }

    // search if request match with a created route
    private function matchRoute($browser, $routes)
    {
        $browser = array_values(array_filter(explode("/", $browser)));
        $keys = array_keys($routes);

        $parsed = "/";
        foreach ($keys as $key) {
            $route = array_values(array_filter(explode("/", $key)));
            if (count($route) === count($browser)) {
                foreach ($route as $index => $r) {
                    $b = $browser[$index];

                    if ($r[0] === ":") {
                        $parsed = $parsed . $r . "/";
                        continue;
                    }

                    if ($r === $b) {
                        $parsed = $parsed . $r . "/";
                    }
                }
            }
        }

        return rtrim($parsed, "/");
    }

    private function addRoute($method, $route, $cb)
    {
        if (!$this->routes[$route]) {
            $handler = new Route($method, $route, $cb);
            $this->routes = array_merge($this->routes, [$route => $handler]);
            return;
        }

        $this->routes[$route]->addMethod($method, $cb);
    }

    function get($route, $cb)
    {
        $this->addRoute("get", $route, $cb);
    }

    function post($route, $cb)
    {
        $this->addRoute("post", $route, $cb);
    }

    function put($route, $cb)
    {
        $this->addRoute("put", $route, $cb);
    }

    function delete($route, $cb)
    {
        $this->addRoute("delete", $route, $cb);
    }

    function listen()
    {
        // search registered route and call
        $matched = $this->matchRoute($this->resources, $this->routes);
        if ($matched) {
            $cb = $this->routes[$matched]->getMethod($this->method);
            $request = new Request($this->resources, $matched);
            $response = new Response();
            $cb($request, $response);
        }
    }
}
