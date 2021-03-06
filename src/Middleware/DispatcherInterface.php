<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Dispatcher Interface
 *
 * An interface for handling third party middleware dispatchers.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface DispatcherInterface extends \Interop\Http\Server\MiddlewareInterface
{
    /**
     * Adds a new middleware or a list of middlewares in the stack.
     *
     * @param  callable|object|string|array $middleware
     * @return self
     */
    public function push($middleware);

    /**
     * Returns the listing of middlewares included.
     *
     * @return array
     */
    public function stack();
}
