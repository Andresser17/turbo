<?php

namespace Turbo;

class Request
{
    public $params;
    public $query;
    public $body;

    function __construct($resources, $route)
    {
        $this->params = $this->parseParams($resources, $route);
        $this->query = $this->parseQuery($_SERVER["QUERY_STRING"]);
        $this->body = [];
    }

    function parseParams($resources, $route)
    {
        $resources = array_values(array_filter(explode("/", $resources)));
        $route = array_values(array_filter(explode("/", $route)));
        $params = [];

        foreach ($route as $i => $r) {
            if ($r[0] === ":") {
                $param = substr($r, 1);
                $params = array_merge($params, [$param => $resources[$i]]);
            }
        }

        return $params;
    }

    function parseQuery($str)
    {
        $queries = [];

        foreach (explode("&", $str) as $query) {
            [$key, $value] = explode("=", $query);
            $queries = array_merge($queries, [$key => $value]);
        }

        return $queries;
    }
}
