<?php

namespace Homework3\_Framework;


class Framework
{

    private $routing;

    public function __construct(bool $hasSession)
    {
        if ($hasSession) {
            session_start();
        }
        $this->routing = new RoutingProvider();
    }

    public function run() {
        $this->routing->init();
        /** @var ControllerHandler $handler */
        $handler = $this->routing->getControllerHandler();
        $handler->sendControllerResponse();
    }

}