<?php

namespace Rougin\Slytherin\Debug;

use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Error Handler Integration
 *
 * An integration for defined error handlers to be included in Slytherin.
 * NOTE: To be removed in v1.0.0. Move to "Integration" directory instead.
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

        $handler = new ErrorHandler($environment);

        if (class_exists('Whoops\Run')) {
            $whoops = new \Whoops\Run;

            $handler = new WhoopsErrorHandler($whoops, $environment);
        }

        if ($environment == 'development') {
            error_reporting(E_ALL);

            ini_set('display_errors', 1);

            $handler->display();
        }

        return $container;
    }
}
