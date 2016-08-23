<?php

namespace Rougin\Slytherin\Test\Fixture;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Test Second Middleware
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TestSecondMiddleware
{
    /**
     * @param  \Psr\Http\Message\ResponseInterface $request
     * @param  \Psr\Http\Message\ServerRequestInterface $response
     * @param  callable|null $next
     * @return callable|null
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $result = (string) $response->getBody();

        $response->getBody()->write($result . ' Second!');

        return $next($request, $response);
    }
}
