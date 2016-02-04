<?php

namespace Rougin\Slytherin\Debug;

use Whoops\Handler\HandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Debugger
 *
 * A simple implementation of a debugger built on top of
 * Filipe Dobreira's Whoops! - php errors for cool kids.
 *
 * http://filp.github.io/whoops
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WhoopsDebugger implements DebuggerInterface
{
    /**
     * @var string
     */
    protected $environment = '';

    /**
     * @var \Whoops\Run
     */
    protected $whoops;

    /**
     * @param \Whoops\Run $whoops
     * @param string      $environment
     */
    public function __construct(Run $whoops, $environment = 'development')
    {
        $this->whoops = $whoops;
        $this->environment = $environment;
    }

    /**
     * Sets up the environment to be used.
     * 
     * @param  string $environment
     * @return void
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Gets the specified environment.
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Returns a listing of handlers.
     * 
     * @return HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->whoops->getHandlers();
    }

    /**
     * Registers the instance as a debugger.
     * 
     * @return Run
     */
    public function display()
    {
        error_reporting(E_ALL);

        if ($this->environment === 'development') {
            $this->setHandler(new PrettyPageHandler);
        }

        return $this->whoops->register();
    }

    /**
     * Sets a handler.
     * 
     * @param callable|HandlerInterface $handler
     */
    public function setHandler($handler)
    {
        $this->whoops->pushHandler($handler);
    }
}
