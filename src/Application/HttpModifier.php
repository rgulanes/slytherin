<?php

namespace Rougin\Slytherin\Application;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;

/**
 * HTTP Modifier
 *
 * Modifies the HTTP by updating the HTTP response with middleware (if included).
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HttpModifier
{
    /**
     * @var array
     */
    protected $middlewares = array();

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Sets the HTTP response and return it to the user.
     *
     * @param  \Psr\Http\Message\ResponseInterface|string      $final
     * @param  \Psr\Http\Message\ResponseInterface|string|null $first
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function setHttpResponse($final, $first = null)
    {
        $response = $this->getHttpResponse($final, $first);

        $protocol = 'HTTP/' . $response->getProtocolVersion();
        $httpCode = $response->getStatusCode() . ' ' . $response->getReasonPhrase();

        header($protocol . ' ' . $httpCode);

        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . implode(',', $value));
        }

        return $response;
    }

    /**
     * Sets the defined middlewares.
     *
     * @param  array                                                 $middlewares
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface|null $middleware
     * @return self
     */
    public function setMiddlewares(array $middlewares = array(), MiddlewareInterface $middleware = null)
    {
        $this->middlewares = $middlewares;

        if (! is_null($middleware) && is_a($middleware, 'Rougin\Slytherin\Middleware\MiddlewareInterface')) {
            $this->middlewares = array_merge($middleware->getQueue(), $this->middlewares);
        }

        if (interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            array_push($this->middlewares, new \Rougin\Slytherin\Middleware\FinalResponse($this->response));
        }

        return $this;
    }

    /**
     * Sets the defined middlewares to the HTTP response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface $middleware
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function invokeMiddleware(ServerRequestInterface $request, MiddlewareInterface $middleware = null)
    {
        $result = null;

        if ($middleware && ! empty($this->middlewares)) {
            $result = $middleware($request, $this->response, $this->middlewares);
        }

        return ($result) ? $this->setHttpResponse($result) : null;
    }

    /**
     * Checks if previous response is available.
     *
     * @param  \Psr\Http\Message\ResponseInterface|string      $final
     * @param  \Psr\Http\Message\ResponseInterface|string|null $first
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getHttpResponse($final, $first = null)
    {
        $response = $this->response;

        if (is_a($first, 'Psr\Http\Message\ResponseInterface')) {
            $response = $first;
        }

        if (is_a($final, 'Psr\Http\Message\ResponseInterface')) {
            $response = $final;
        } else {
            $response->getBody()->write($final);
        }

        return $response;
    }
}
