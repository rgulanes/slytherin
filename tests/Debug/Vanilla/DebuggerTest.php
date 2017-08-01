<?php

namespace Rougin\Slytherin\Debug\Vanilla;

/**
 * Debugger Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DebuggerTest extends \PHPUnit_Framework_TestCase
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
        $this->debugger = new \Rougin\Slytherin\Debug\Vanilla\Debugger;
    }

    /**
     * Tests the display() method.
     *
     * @return void
     */
    public function testDisplayMethod()
    {
        $this->assertEquals('', $this->debugger->display());
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
