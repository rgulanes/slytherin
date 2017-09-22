<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Request Handler
 *
 * Calls the callback with a specified HTTP request.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @author  Rasmus Schultz <rasmus@mindplay.dk>
 */
class RequestHandler implements \Interop\Http\Server\RequestHandlerInterface
{
    /**
     * @var callable|array
     */
    protected $callback;

    /**
     * @param callable|null $callback
     */
    public function __construct($callback = null)
    {
        $this->callback = $callback ?: array($this, 'response');
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        return call_user_func($this->callback, $request);
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->handle($request);
    }

    /**
     * Returns an empty \Psr\Http\Message\ResponseInterface
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function response()
    {
        return new \Rougin\Slytherin\Http\Response;
    }
}
