<?php

namespace Homework3\_Framework;

class RoutingProvider
{

    public const EMPTY_ROUTE = 0;
    public const MAIN_ROUTE = 1;
    public const ACTION_ROUTE = 2;
    public const ITEM_ROUTE = 3;
    public const WRONG_ROUTE = 4;

    private $pathInfo;
    private $pathSplit;
    private $routeType;

    private $requestMethod;

    private $loginState;

    public static $indexRoutes = ['login', 'logout'];

    /**
     * @var ControllerHandler $handler
     */
    private $handler;

    /**
     * RoutingProvider constructor.
     */
    public function __construct()
    {
        $this->pathInfo = '/';
        if (!empty($_SERVER['PATH_INFO'])) {
            $this->pathInfo = $_SERVER['PATH_INFO'];
        }
        $this->pathSplit = explode('/', ltrim($this->pathInfo));
        $this->routeType = $this->identifyRouteType($this->pathSplit);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->loginState = false;
    }

    /**
     * @param array $pathSplit
     * @return int
     */
    private function identifyRouteType(array $pathSplit): int
    {
        switch (count($pathSplit) -1) {
            case 0:
                $routeType = self::EMPTY_ROUTE;
                break;
            case 1:
                $routeType = self::MAIN_ROUTE;
                break;
            case 2:
                $routeType = self::ACTION_ROUTE;
                break;
            case 3:
                $routeType = self::ITEM_ROUTE;
                break;
            default:
                $routeType = self::WRONG_ROUTE;
                break;
        }

        return $routeType;
    }

    /**
     * Init routing with catching possible errors under the route handling.
     */
    public function init()
    {
        if (!empty($_SERVER['QUERY_STRING'])) {
            $this->forwardError('Query String not empty error inside RouteProvider');
        }

        $path = $_SERVER['PATH_INFO'] ?? '';
        $this->refreshLoginState();

        if (empty($path) || ltrim($path) == '/') {
            $this->forwardHome();
        } else {
            try {
                $this->handleRoute();
            } catch (FrameworkException $exception) {
                $this->forwardError('Handling Route error inside RoutingProvider. ' . $exception->getMessage());
            }
        }
    }

    /**
     * @return ControllerHandler
     */
    public function getControllerHandler(): ControllerHandler {
        return $this->handler;
    }

    /**
     * Forwarding to Home page.
     */
    private function forwardHome() {
        $this->handler = new ControllerHandler();
        $this->handler->handleHome($this->loginState);
    }

    /**
     * Forwarding to Error page.
     *
     * @param string $message
     */
    private function forwardError(string $message) {
        $this->handler = new ControllerHandler();
        $this->handler->handleError($message);
    }

    /**
     * @throws FrameworkException
     */
    private function handleRoute() {
        $pathSplit = $this->pathSplit;
        $routeType = $this->routeType;

        $this->handler = new ControllerHandler();
        switch ($routeType) {
            case self::MAIN_ROUTE:
                $mainRoute = $pathSplit[self::MAIN_ROUTE];
                if (in_array($mainRoute, self::$indexRoutes)) {
                    $this->handler->handleIndex($mainRoute, $this->loginState);
                } else {
                    $this->handler->handleMainRoute($mainRoute);
                }
                break;
            case self::ACTION_ROUTE:
                $action = $pathSplit[self::ACTION_ROUTE];
                $this->handler->handleActionRoute($pathSplit[self::MAIN_ROUTE], $action);
                break;
            case self::ITEM_ROUTE:
                $action = $pathSplit[self::ACTION_ROUTE];
                $item = $pathSplit[self::ITEM_ROUTE];
                $this->handler->handleActionRoute($pathSplit[self::MAIN_ROUTE], $action, $item);
                break;
            default:
                $this->forwardError('Missing Route error inside RoutingProvider.');
        }
    }

    /**
     * Update the value of loginState class variable.
     */
    public function refreshLoginState()
    {
        if (isset($_SESSION['login_name'])) {
            // User should be logged in to go on expense page(s)
            $this->loginState = true;
        }
    }

}