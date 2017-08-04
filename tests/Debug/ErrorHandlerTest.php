<?php

namespace Rougin\Slytherin\Debug;

/**
 * ErrorHandler Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Debug\ErrorHandlerInterface
     */
    protected $handler;

    /**
     * @var string
     */
    protected $environment = 'production';

    /**
     * Sets up the error handler.
     *
     * @return void
     */
    public function setUp()
    {
        $this->handler = new \Rougin\Slytherin\Debug\ErrorHandler;
    }

    /**
     * Tests the display() method.
     *
     * @return void
     */
    public function testDisplayMethod()
    {
        $this->assertEquals('', $this->handler->display());
    }

    /**
     * Tests if the error handler is implemented in ErrorHandlerInterface.
     *
     * @return void
     */
    public function testErrorHandlerInterface()
    {
        $interface = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';

        $this->assertInstanceOf($interface, $this->handler);
    }
}
