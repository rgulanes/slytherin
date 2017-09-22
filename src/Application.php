<?php

namespace Rougin\Slytherin;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application
 *
 * Integrates all specified components into the application.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Application
{
    const MIDDLEWARE_DISPATCHER = 'Rougin\Slytherin\Middleware\DispatcherInterface';

    const SERVER_REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    /**
     * @var \Rougin\Slytherin\Integration\Configuration
     */
    protected $config;

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected static $container;

    /**
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null, Integration\Configuration $config = null)
    {
        $this->config = $config ?: new Integration\Configuration;

        static::$container = $container ?: new Container\Container;
    }

    /**
     * Returns the static instance of the specified container.
     *
     * @return \Psr\Container\ContainerInterface
     */
    public static function container()
    {
        return static::$container;
    }

    /**
     * Handles the ServerRequestInterface to convert it to a ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        $callback = new Application\CallbackHandler(self::$container);

        if (static::$container->has(self::MIDDLEWARE_DISPATCHER)) {
            $middleware = static::$container->get(self::MIDDLEWARE_DISPATCHER);

            $handler = new Middleware\RequestHandler($callback);

            $result = $middleware->process($request, $handler);
        }

        return (isset($result)) ? $result : $callback($request);
    }

    /**
     * Adds the specified integrations to the container.
     *
     * @param  array|string                                $integrations
     * @param  \Rougin\Slytherin\Integration\Configuration $config
     * @return self
     */
    public function integrate($integrations, Integration\Configuration $config = null)
    {
        list($config, $container) = array($config ?: $this->config, static::$container);

        array_map(function ($item) use (&$container, $config) {
            $integration = new $item;

            $container = $integration->define($container, $config);

            return $integration;
        }, is_string($integrations) ? array($integrations) : $integrations);

        static::$container = $container;

        return $this;
    }

    /**
     * Emits the headers from response and runs the application.
     *
     * @return void
     */
    public function run()
    {
        $response = $this->handle(static::$container->get(self::SERVER_REQUEST));

        list($code, $reason) = array($response->getStatusCode(), $response->getReasonPhrase());

        header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $code, $reason));

        list($body, $headers) = array($response->getBody(), $response->getHeaders());

        array_map(function ($name, $values) {
            header($name . ': ' . implode(',', $values));

            return $name . ': ' . implode(',', $values);
        }, array_keys($headers), $response->getHeaders());

        echo (string) $body;
    }
}
