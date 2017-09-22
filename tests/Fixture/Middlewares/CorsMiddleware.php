<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Interop\Http\Server\RequestHandlerInterface;

use Rougin\Slytherin\Http\Response;

/**
 * CORS Middleware
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CorsMiddleware implements \Interop\Http\Server\MiddlewareInterface
{
    /**
     * @var array
     */
    protected $allowed = array('*');

    /**
     * @var array
     */
    protected $methods = array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS');

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
        $response = $request->getMethod() === 'OPTIONS' ? new Response : $handler->handle($request);

        $response = $response->withHeader('Access-Control-Allow-Origin', $this->allowed);
        $response = $response->withHeader('Access-Control-Allow-Methods', $this->methods);

        return $response;
    }
}
