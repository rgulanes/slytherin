<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * First Middleware
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class FirstMiddleware
{
    /**
     * @param  \Psr\Http\Message\ResponseInterface $request
     * @param  \Psr\Http\Message\ServerRequestInterface $response
     * @param  callable|null $next
     * @return callable|null
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $response->getBody()->write('First!');

        return $next($request, $response);
    }
}
