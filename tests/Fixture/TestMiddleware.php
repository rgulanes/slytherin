<?php

namespace Rougin\Slytherin\Test\Fixture;

use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Test Middleware
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TestMiddleware implements MiddlewareInterface
{
    /**
     * @param  \Psr\Http\Message\ResponseInterface $request
     * @param  \Psr\Http\Message\ServerRequestInterface $response
     * @param  callable|null $next
     * @return callable|null
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $response->getBody()->write('Loaded with middleware');

        return $response;
    }
}
