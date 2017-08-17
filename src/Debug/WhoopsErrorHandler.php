<?php

namespace Rougin\Slytherin\Debug;

/**
 * Whoops Error Handler
 *
 * A simple implementation of an error handler built on top of Filipe Dobreira's
 * Whoops. NOTE: To be removed in v1.0.0. Use "ErrorHandlerIntegration" instead.
 *
 * http://filp.github.io/whoops
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WhoopsErrorHandler implements ErrorHandlerInterface
{
    /**
     * @var string
     */
    protected $environment = '';

    /**
     * @var \Whoops\Run|null
     */
    protected $whoops = null;

    /**
     * @param string           $environment
     * @param \Whoops\Run|null $whoops
     */
    public function __construct($environment = 'development', \Whoops\Run $whoops = null)
    {
        $this->environment = $environment;

        $this->whoops = $whoops ?: new \Whoops\Run;
    }

    /**
     * Registers the instance as an error handler.
     *
     * @return \Whoops\Run
     */
    public function display()
    {
        $handler = new \Whoops\Handler\PrettyPageHandler;

        $this->__call('pushHandler', array($handler));

        return $this->whoops->register();
    }

    /**
     * Calls methods from the \Whoops\Run instance.
     *
     * @param  string $method
     * @param  mixed  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->whoops, $method), $parameters);
    }
}
