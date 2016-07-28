<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use Closure;
use FastRoute;
use UnexpectedValueException;
use FastRoute\Dispatcher as FastRouteDispatcher;

use Rougin\Slytherin\Dispatching\DispatcherInterface;

/**
 * FastRoute Dispatcher
 *
 * A simple implementation of dispatcher that is built on top of FastRoute.
 *
 * https://github.com/nikic/FastRoute
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @param \Rougin\Slytherin\Dispatching\FastRoute\Router $router
     */
    public function __construct(Router $router)
    {
        $this->dispatcher = FastRoute\simpleDispatcher($router->getRoutes());
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     * 
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|string
     */
    public function dispatch($httpMethod, $uri)
    {
        $className   = '';
        $classMethod = '';
        $parameters  = [];

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRouteDispatcher::NOT_FOUND:
                throw new UnexpectedValueException("Route \"$uri\" not found");
            case FastRouteDispatcher::METHOD_NOT_ALLOWED:
                throw new UnexpectedValueException("Used method's not allowed");
            case FastRouteDispatcher::FOUND:
                $isClosure = $routeInfo[1] instanceof Closure;

                if (is_object($routeInfo[1]) && $isClosure) {
                    return [$routeInfo[1], $routeInfo[2]];
                }

                $className   = $routeInfo[1][0];
                $classMethod = $routeInfo[1][1];
                $parameters  = $routeInfo[2];

                break;
        }

        return [[$className, $classMethod], $parameters];
    }
}
