<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Interop\Http\Server\RequestHandlerInterface;

/**
 * Callable Middleware Wrapper
 *
 * Converts callables into PSR-15 middlewares.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @author  Rasmus Schultz <rasmus@mindplay.dk>
 */
class CallableMiddlewareWrapper implements \Interop\Http\Server\MiddlewareInterface
{
    /**
     * @var callable
     */
    protected $middleware;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param callable                                 $middleware
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function __construct($middleware, ResponseInterface $response = null)
    {
        $this->middleware = $middleware;

        $this->response = $response;
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
        $middleware = $this->middleware;

        if ($this->response instanceof ResponseInterface) {
            $handler = function ($request) use ($handler) {
                return $handler->handle($request);
            };

            return $middleware($request, $this->response, $handler);
        }

        return $middleware($request, $handler);
    }
}
