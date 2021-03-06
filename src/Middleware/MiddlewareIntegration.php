<?php

namespace Rougin\Slytherin\Middleware;

use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Middleware Integration
 *
 * An integration for Slytherin's Middleware packages.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MiddlewareIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
{
    /**
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  \Rougin\Slytherin\Integration\Configuration    $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        $response = $container->get('Psr\Http\Message\ResponseInterface');

        $stack = $config->get('app.middlewares', array());

        $dispatcher = $this->dispatcher($response, $stack);

        $container->set('Rougin\Slytherin\Middleware\DispatcherInterface', $dispatcher);

        return $container;
    }

    /**
     * Returns the middleware dispatcher to be used.
     *
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @param  array                               $stack
     * @return \Rougin\Slytherin\Middleware\DispatcherInterface
     */
    protected function dispatcher(\Psr\Http\Message\ResponseInterface $response, $stack)
    {
        $dispatcher = new Dispatcher($stack, $response);

        return $dispatcher;
    }
}
