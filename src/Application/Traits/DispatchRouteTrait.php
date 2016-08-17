<?php

namespace Rougin\Slytherin\Application\Traits;

use Psr\Http\Message\ServerRequestInterface;

use Rougin\Slytherin\Dispatching\DispatcherInterface;

trait DispatchRouteTrait
{
    /**
     * Gets the result from the dispatcher.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return array
     */
    private function dispatchRoute(DispatcherInterface $dispatcher, ServerRequestInterface $request)
    {
        $method = $request->getMethod();
        $path   = $request->getUri()->getPath();
        $post   = $request->getParsedBody();

        if (isset($post['_method'])) {
            $method = strtoupper($post['_method']);
        }

        // Gets the requested route from the dispatcher
        $route = $dispatcher->dispatch($method, $path);

        // Extract the result into variables
        list($function, $parameters, $middlewares) = $route;

        return [ [ $function, $parameters ], $middlewares ];
    }
}