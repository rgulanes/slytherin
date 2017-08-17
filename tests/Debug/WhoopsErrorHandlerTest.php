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

        $this->debugger = new \Rougin\Slytherin\Debug\WhoopsErrorHandler;
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
