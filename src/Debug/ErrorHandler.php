<?php

namespace Rougin\Slytherin\Debug;

/**
 * Error Handler
 *
 * A simple implementation of a debugger.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ErrorHandler implements ErrorHandlerInterface
{
    /**
     * @var string
     */
    protected $environment = '';

    /**
     * @param string $environment
     */
    public function __construct($environment = 'development')
    {
        $this->environment = $environment;
    }

    /**
     * Registers the instance as an error handler.
     *
     * @return void
     */
    public function display()
    {
        error_reporting(E_ALL);

        ini_set('display_errors', 1);
    }
}
