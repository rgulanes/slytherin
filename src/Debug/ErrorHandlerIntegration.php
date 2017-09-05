<?php

namespace Rougin\Slytherin\Debug;

use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Error Handler Integration
 *
 * An integration for defined error handlers to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ErrorHandlerIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
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
        $environment = $config->get('app.environment', 'development');

        list($handler, $whoops) = array(new ErrorHandler($environment), 'Whoops\Run');

        if (class_exists($whoops) === true) {
            $run = $container->has($whoops) ? $container->get($whoops) : null;

            $handler = new WhoopsErrorHandler($environment, $run);
        }

        $environment !== 'development' || $handler->display();

        return $container;
    }
}
