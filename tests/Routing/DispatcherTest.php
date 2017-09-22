<?php

namespace Rougin\Slytherin\Routing;

/**
 * Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the dispatcher.
     *
     * @return void
     */
    public function setUp()
    {
        $routes = array(array('TEST', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index'));

        $router = new Router($routes);

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');
        $router->post('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@store');

        $router->get('/hi', function () {
            return 'Hi and this is a callback';
        });

        $this->dispatcher = new Dispatcher($router);
    }
}
