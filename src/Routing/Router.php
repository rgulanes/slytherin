<?php

namespace Rougin\Slytherin\Routing;

/**
 * Dispatcher
 *
 * A simple implementation of a router that is based on RouterInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Router implements RouterInterface
{
    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @var array
     */
    protected $allowed = array('DELETE', 'GET', 'PATCH', 'POST', 'PUT');

    /**
     * @param array $routes
     */
    public function __construct(array $routes = array())
    {
        foreach ($routes as $route) {
            list($httpMethod, $uri, $handler) = $route;

            $middlewares = (isset($route[3])) ? $route[3] : array();
            $middlewares = is_string($middlewares) ? array($middlewares) : $middlewares;

            $this->add($httpMethod, $uri, $handler, $middlewares);
        }
    }

    /**
     * Adds a new raw route.
     *
     * @param  string       $httpMethod
     * @param  string       $route
     * @param  array|string $handler
     * @param  array|string $middlewares
     * @return self
     */
    public function add($httpMethod, $route, $handler, $middlewares = array())
    {
        $route = array($httpMethod, $route, $handler, $middlewares);

        array_push($this->routes, $this->parse($route));

        return $this;
    }

    /**
     * Checks if the specified route is available in the router.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return boolean
     */
    public function has($httpMethod, $uri)
    {
        return $this->retrieve($httpMethod, $uri) !== null;
    }

    /**
     * Merges a listing of parsed routes to current one.
     *
     * @param  array $routes
     * @return self
     */
    public function merge(array $routes)
    {
        $this->routes = array_merge($this->routes, $routes);

        return $this;
    }

    /**
     * Returns a specific route based on the specified HTTP method and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|null
     */
    public function retrieve($httpMethod, $uri)
    {
        $route = array($httpMethod, $uri);

        $routes = array_map(function ($route) {
            return array($route[0], $route[1]);
        }, $this->routes);

        $key = array_search($route, $routes);

        return $key !== false ? $this->routes[$key] : null;
    }

    /**
     * Returns a listing of available routes.
     *
     * @param  boolean $parsed
     * @return array
     */
    public function routes($parsed = false)
    {
        return $this->routes;
    }

    /**
     * Adds a listing of routes specified for RESTful approach.
     *
     * @param  string       $route
     * @param  string       $class
     * @param  array|string $middlewares
     * @return self
     */
    public function restful($route, $class, $middlewares = array())
    {
        $middlewares = (is_string($middlewares)) ? array($middlewares) : $middlewares;

        $this->add('GET', '/' . $route, $class . '@index', $middlewares);
        $this->add('POST', '/' . $route, $class . '@store', $middlewares);

        $this->add('DELETE', '/' . $route . '/:id', $class . '@delete', $middlewares);
        $this->add('GET', '/' . $route . '/:id', $class . '@show', $middlewares);
        $this->add('PATCH', '/' . $route . '/:id', $class . '@update', $middlewares);
        $this->add('PUT', '/' . $route . '/:id', $class . '@update', $middlewares);

        return $this;
    }

    /**
     * Sets a prefix for the succeeding route endpoints.
     *
     * @param  string      $prefix
     * @param  string|null $namespace
     * @return self
     */
    public function prefix($prefix = '', $namespace = null)
    {
        $this->namespace = ($namespace !== null) ? $namespace . '\\' : $this->namespace;

        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Parses the route.
     *
     * @param  array $route
     * @return array
     */
    protected function parse($route)
    {
        $route[0] = strtoupper($route[0]);
        $route[1] = str_replace('//', '/', $this->prefix . $route[1]);
        $route[2] = (is_string($route[2])) ? explode('@', $route[2]) : $route[2];
        $route[3] = (! is_array($route[3])) ? array($route[3]) : $route[3];

        ! is_array($route[2]) || $route[2][0] = $this->namespace . $route[2][0];

        return $route;
    }

    /**
     * Calls methods from this class in HTTP method format.
     *
     * @param  string $method
     * @param  mixed  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array(strtoupper($method), $this->allowed)) {
            array_unshift($parameters, strtoupper($method));

            return call_user_func_array(array($this, 'add'), $parameters);
        }

        $message = '"' . strtoupper($method) . '" is not a valid HTTP method';

        throw new \BadMethodCallException($message);
    }
}
