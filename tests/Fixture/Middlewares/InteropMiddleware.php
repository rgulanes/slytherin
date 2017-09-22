<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Interop\Http\Server\RequestHandlerInterface;

/**
 * Interop Middleware
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class InteropMiddleware implements \Interop\Http\Server\MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface     $request
     * @param  \Interop\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $response = $handler->handle($request)->withStatus(500);

        return $response->withHeader('X-Slytherin', time());
    }
}
