<?php

namespace Homework3\_Framework;

class RoutingProvider
{

    private $pathInfo;
    private $pathSplit;

    private $requestMethod;

    private $queryString;

    public function __construct()
    {
        $this->pathInfo = '/';
        if (isset($_SERVER['PATH_INFO'])) {
            $this->pathInfo = $_SERVER['PATH_INFO'];
        }
        $this->pathSplit = explode('/', ltrim($this->pathInfo));

        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        $this->queryString = $_SERVER['QUERY_STRING'];
        if (!empty($_SERVER['QUERY_STRING'])) {
            new \HttpException("Query string should be empty", 400);
        }
    }

}