<?php

namespace Rougin\Slytherin\Application;

/**
 * Container Interface Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ContainerInterfaceTest extends ApplicationTestCases
{
    /**
     * Prepares the application instance.
     *
     * @return void
     */
    public function setUp()
    {
        $container = new \Rougin\Slytherin\Container\Container;

        $dispatcher = new \Rougin\Slytherin\Routing\Dispatcher($this->router());

        $middleware = new \Rougin\Slytherin\Middleware\Dispatcher;

        $headers = array('X-SLYTHERIN-HEADER' => array('foobar'));

        $response = new \Rougin\Slytherin\Http\Response(200, null, $headers);

        $container->set('Psr\Http\Message\ServerRequestInterface', $this->request('GET', '/'));
        $container->set('Psr\Http\Message\ResponseInterface', $response);
        $container->set('Rougin\Slytherin\Routing\DispatcherInterface', $dispatcher);
        $container->set('Rougin\Slytherin\Middleware\DispatcherInterface', $middleware);

        $this->application = new \Rougin\Slytherin\Application($container);
    }
}
