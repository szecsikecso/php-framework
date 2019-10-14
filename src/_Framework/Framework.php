<?php

namespace Homework3\_Framework;


class Framework
{

    private $routing;

    private $entityHandler;

    private $exceptionHandler;

    public function __construct()
    {
        $this->routing = new RoutingProvider();
        $this->entityHandler = new EntityHandler();
        $this->exceptionHandler = new ExceptionHandler();
    }

}