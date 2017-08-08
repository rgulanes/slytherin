<?php

namespace Rougin\Slytherin\Debug\Whoops;

/**
 * Whoops Debugger Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WhoopsErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Debug\DebuggerInterface
     */
    protected $debugger;

    /**
     * @var string
     */
    protected $environment = 'production';

    /**
     * Sets up the debugger.
     *
     * @return void
     */
    public function setUp()
    {
        class_exists('Whoops\Run') || $this->markTestSkipped('Whoops is not installed.');

        $whoops = new \Whoops\Run;

        $this->debugger = new \Rougin\Slytherin\Debug\WhoopsErrorHandler($whoops);
    }

    /**
     * Tests if the specified handler is in the debugger's list of handlers.
     *
     * @return void
     */
    public function testSetHandlerMethod()
    {
        $this->debugger->setHandler(new \Whoops\Handler\PrettyPageHandler);

        $handlers = $this->debugger->getHandlers();

        $this->assertInstanceOf('Whoops\Handler\PrettyPageHandler', $handlers[0]);
    }

    /**
     * Tests the display() method.
     *
     * @return void
     */
    public function testDisplayMethod()
    {
        $this->assertInstanceOf('Whoops\Run', $this->debugger->display());
    }

    /**
     * Tests if the debugger is implemented in ErrorHandlerInterface.
     *
     * @return void
     */
    public function testDebuggerInterface()
    {
        $interface = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';

        $this->assertInstanceOf($interface, $this->debugger);
    }
}
