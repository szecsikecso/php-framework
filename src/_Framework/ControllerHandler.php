<?php

namespace Homework3\_Framework;

class ControllerHandler
{

    public const CONTROLLER = 'Controller';
    public const CONTROLLER_NAMESPACE = 'Homework3\\Controller\\';
    public const CONTROLLER_DEFAULT_ACTION = 'index';
    public const CONTROLLER_ACTION_POSTFIX = 'Action';

    /**
     * @var null|mixed The response created here will be sent by Framework.
     */
    private $response;

    public function __construct()
    {
        $this->response = null;
    }

    /**
     * @return mixed|null
     */
    public function sendControllerResponse()
    {
        return $this->response;
    }

    /**
     * @param bool $loginState
     */
    public function handleHome(bool $loginState): void
    {
        $this->handleIndex(self::CONTROLLER_DEFAULT_ACTION, $loginState);
    }

    /**
     * @param string $actionName
     * @param bool $loginState
     */
    public function handleIndex(string $actionName, bool $loginState): void
    {
        $callable_class = new IndexController();
        $actionName .= self::CONTROLLER_ACTION_POSTFIX;
        $this->response = call_user_func_array([$callable_class, $actionName], [$loginState]);
    }

    /**
     * @param string $message
     */
    public function handleError(string $message): void
    {
        $callable_class = new IndexController();
        $this->response = call_user_func_array([$callable_class, 'handle405'], [$message]);
    }

    /**
     * @param string $controllerName
     * @throws FrameworkException
     */
    public function handleMainRoute(string $controllerName): void
    {
        $callable_class = $this->getControllerObject($controllerName);
        $actionName = self::CONTROLLER_DEFAULT_ACTION . self::CONTROLLER_ACTION_POSTFIX;
        $this->response = call_user_func_array([$callable_class, $actionName], []);
    }

    /**
     * @param string $controllerName
     * @param string $actionName
     * @param int $entityId
     * @throws FrameworkException
     */
    public function handleActionRoute(string $controllerName, string $actionName, int $entityId = 0): void
    {
        $callable_class = $this->getControllerObject($controllerName);
        $actionName .= self::CONTROLLER_ACTION_POSTFIX;
        if (!method_exists($callable_class, $actionName)) {
            throw new FrameworkException('Controller ' . $actionName . ' action missing.');
        }
        $this->response = call_user_func_array([$callable_class, $actionName], [$entityId]);
    }

    /**
     * @param string $controllerName
     * @return FrameworkController
     * @throws FrameworkException
     */
    private function getControllerObject(string $controllerName): FrameworkController
    {
        $controllerName = ucfirst($controllerName);
        $controllerName = self::CONTROLLER_NAMESPACE . $controllerName . self::CONTROLLER;
        if (!class_exists($controllerName)) {
            throw new FrameworkException('Controller class missing.');
        }

        $controllerObject = new $controllerName();
        $actionName = self::CONTROLLER_DEFAULT_ACTION . self::CONTROLLER_ACTION_POSTFIX;
        if (!method_exists($controllerObject, $actionName)) {
            throw new FrameworkException('Controller default action missing.');
        }

        return $controllerObject;
    }

}