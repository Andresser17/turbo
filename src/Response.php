<?php

namespace Turbo;

class Response
{
    public $code;

    function __construct()
    {
        $this->code = 200;
    }

    function status($code)
    {
        $this->code = $code;
        return $this;
    }

    function send($body)
    {
        http_response_code($this->code);
        echo $body;
    }

    function json($body)
    {
        http_response_code($this->code);
        echo json_encode($body);
    }
}
