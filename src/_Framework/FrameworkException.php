<?php

namespace Homework3\_Framework;


use Exception;
use Throwable;

class FrameworkException extends Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}