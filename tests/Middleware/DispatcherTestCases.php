<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Dispatcher Test Cases
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DispatcherTestCases extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Middleware\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * Tests DispatcherInterface::process with a double pass callback.
     *
     * @return void
     */
    public function testProcessMethodWithDoublePassCallback()
    {
        $this->exists(get_class($this->dispatcher));

        $callback = function ($request, $response, $next) {
            $response = $next($request, $response)->withStatus(404);

            return $response->withHeader('X-Slytherin', time());
        };

        $this->dispatcher->push($callback);

        $this->assertEquals(404, $this->response()->getStatusCode());
    }

    /**
     * Tests DispatcherInterface::process with a single pass callback.
     *
     * @return void
     */
    public function testProcessMethodWithSinglePassCallback()
    {
        $this->exists(get_class($this->dispatcher));

        $time = time();

        $callback = function ($request, $next) use ($time) {
            $response = $next($request);

            return $response->withHeader('X-Slytherin', $time);
        };

        $this->dispatcher->push($callback);

        $this->assertEquals(array($time), $this->response()->getHeader('X-Slytherin'));
    }

    /**
     * Tests DispatcherInterface::process with \Interop\Http\Server\RequestHandlerInterface callback.
     *
     * @return void
     */
    public function testProcessMethodWithDelagateInterfaceCallback()
    {
        $this->exists(get_class($this->dispatcher));

        $callback = function ($request, $handler) {
            $response = $handler->handle($request);

            return $response->withHeader('Content-Type', 'application/json');
        };

        $this->dispatcher->push($callback);

        $this->assertEquals(array('application/json'), $this->response()->getHeader('Content-Type'));
    }

    /**
     * Tests DispatcherInterface::process with string.
     *
     * @return void
     */
    public function testProcessMethodWithString()
    {
        $this->exists(get_class($this->dispatcher));

        $this->dispatcher->push('Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware');

        $this->assertEquals(500, $this->response()->getStatusCode());
    }

    /**
     * Tests DispatcherInterface::push with array.
     *
     * @return void
     */
    public function testPushMethodWithArray()
    {
        $this->exists(get_class($this->dispatcher));

        $stack = array();

        array_push($stack, 'Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware');
        array_push($stack, 'Rougin\Slytherin\Middleware\FinalResponse');

        $this->dispatcher->push($stack);

        $this->assertEquals($stack, $this->dispatcher->stack());
    }

    /**
     * Tests DispatcherInterface::stack.
     *
     * @return void
     */
    public function testStackMethod()
    {
        $this->exists(get_class($this->dispatcher));

        $this->dispatcher->push('Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware');
        $this->dispatcher->push('Rougin\Slytherin\Middleware\FinalResponse');

        $this->assertCount(2, $this->dispatcher->stack());
    }

    /**
     * Processes the defined middleware dispatcher and return its response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function response()
    {
        $server = array();

        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new \Rougin\Slytherin\Http\ServerRequest($server);

        return $this->dispatcher->process($request, new RequestHandler);
    }

    /**
     * Verifies the specified dispatcher if it exists.
     *
     * @param  string $dispatcher
     * @return void
     */
    protected function exists($dispatcher)
    {
    }
}
