<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Rougin\Slytherin\Http\Response;
use Interop\Http\Server\RequestHandlerInterface;

/**
 * Body Parameters Middleware
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BodyParametersMiddleware implements \Interop\Http\Server\MiddlewareInterface
{
    /**
     * @var array
     */
    protected $complex = array('PUT', 'DELETE');

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
        if (in_array($request->getMethod(), $this->complex)) {
            parse_str(file_get_contents('php://input'), $body);

            $request = $request->withParsedBody($body);
        }

        return $handler->handle($request);
    }
}
