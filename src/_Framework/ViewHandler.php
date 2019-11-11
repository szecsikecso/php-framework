<?php

namespace Homework3\_Framework;

class ViewHandler
{

    public const TWIG_POSTFIX = '.html.twig';
    public const TWIG_DIRECTORY_SEPARATOR = '/';

    public const NEW = 'new';
    public const SHOW = 'read';
    public const UPDATE = 'update';
    public const DELETE = 'delete';

    private $machineName;
    private $indexView;
    private $crudViews = [];

    public function __construct(string $machineName, bool $crud)
    {
        $this->machineName = $machineName;
        $this->indexView = $machineName . self::TWIG_DIRECTORY_SEPARATOR . ControllerHandler::CONTROLLER_DEFAULT_ACTION .
            self::TWIG_POSTFIX;

        if ($crud) {
            $this->crudViews = [
                self::NEW => $machineName . self::TWIG_DIRECTORY_SEPARATOR . self::NEW . self::TWIG_POSTFIX,
                self::SHOW => $machineName . self::TWIG_DIRECTORY_SEPARATOR . self::SHOW . self::TWIG_POSTFIX,
                self::UPDATE => $machineName . self::TWIG_DIRECTORY_SEPARATOR . self::UPDATE . self::TWIG_POSTFIX,
                self::DELETE => $machineName . self::TWIG_DIRECTORY_SEPARATOR . self::DELETE . self::TWIG_POSTFIX,
            ];
        }
    }

    public function handleCustomView(string $action)
    {
        return $this->machineName . self::TWIG_DIRECTORY_SEPARATOR . $action . self::TWIG_POSTFIX;
    }

}