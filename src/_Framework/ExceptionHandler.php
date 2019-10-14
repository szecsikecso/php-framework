<?php

namespace Homework3\_Framework;


class ExceptionHandler
{

    public function __construct()
    {
    }

    public function handle(string $message)
    {
        echo $message;
    }

}