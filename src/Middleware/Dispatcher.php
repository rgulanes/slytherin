<?php

namespace Rougin\Slytherin\Middleware;

use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Rougin\Slytherin\Middleware\RequestHandler;

/**
 * Dispatcher
 *
 * A simple implementation of a middleware dispatcher.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @author  Rasmus Schultz <rasmus@mindplay.dk>
 */
class Dispatcher implements \Rougin\Slytherin\Middleware\DispatcherInterface
{
    const SINGLE_PASS = 0;

    const DOUBLE_PASS = 1;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $stack = array();

    /**
     * @param array                                    $stack
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function __construct(array $stack = array(), ResponseInterface $response = null)
    {
        $this->response = $response ?: new \Rougin\Slytherin\Http\Response;

        $this->stack = $stack;
    }

    /**
     * Processes an incoming server request and return a response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface     $request
     * @param  \Interop\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $original = $this->stack;

        $this->push(function ($request) use ($handler) {
            return $handler->handle($request);
        });

        foreach ($this->stack as $index => $middleware) {
            $middleware = (is_string($middleware)) ? new $middleware : $middleware;

            $this->stack[$index] = $this->transform($middleware);
        }

        $resolved = $this->resolve(0);

        array_pop($this->stack);

        $this->stack = $original;

        return $resolved($request);
    }

    /**
     * Adds a new middleware or a list of middlewares in the stack.
     *
     * @param  callable|object|string|array $middleware
     * @return self
     */
    public function push($middleware)
    {
        if (is_array($middleware)) {
            $stack = array_merge($this->stack, $middleware);

            $this->stack = $stack;
        } else {
            array_push($this->stack, $middleware);
        }

        return $this;
    }

    /**
     * Returns the listing of middlewares included.
     *
     * @return array
     */
    public function stack()
    {
        return $this->stack;
    }

    /**
     * Checks if the approach of the specified middleware is either single or double pass.
     *
     * @param  callable|object $middleware
     * @return boolean
     */
    protected function approach($middleware)
    {
        if (is_a($middleware, 'Closure')) {
            $object = new \ReflectionFunction($middleware);
        } else {
            $object = new \ReflectionMethod(get_class($middleware), '__invoke');
        }

        return count($object->getParameters()) == 2;
    }

    /**
     * Resolves the whole stack through its index.
     *
     * @param  integer $index
     * @return \Interop\Http\Server\RequestHandlerInterface
     */
    protected function resolve($index)
    {
        $callback = null;

        $stack = $this->stack;

        if (isset($this->stack[$index])) {
            $item = $stack[$index];

            $next = $this->resolve($index + 1);

            $callback = function ($request) use ($index, $item, $next) {
                return $item->process($request, $next);
            };
        }

        return new RequestHandler($callback);
    }

    /**
     * Transforms the specified middleware into a PSR-15 middleware.
     *
     * @param  callable|object $middleware
     * @param  boolean         $wrap
     * @return \Interop\Http\Server\MiddlewareInterface
     */
    protected function transform($middleware, $wrap = true)
    {
        if (! is_a($middleware, 'Interop\Http\Server\MiddlewareInterface')) {
            $approach = $this->approach($middleware);

            $response = ($approach == self::SINGLE_PASS) ? $this->response : null;

            $wrapper = new CallableMiddlewareWrapper($middleware, $response);

            $middleware = ($wrap) ? $wrapper : $middleware;
        }

        return $middleware;
    }
}
