<?php

namespace Rougin\Slytherin\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Container
 *
 * A simple container that is implemented on \Psr\Container\ContainerInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $extra;

    /**
     * NOTE: To be removed in v1.0.0. Use "protected" visibility instead.
     *
     * @var array
     */
    public $instances = array();

    /**
     * @param array                                  $instances
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(array $instances = array(), PsrContainerInterface $container = null)
    {
        $this->instances = $instances;
 
        $this->extra = $container ?: new ReflectionContainer;
    }

    /**
     * Creates an alias for a specified class.
     *
     * @param string $id
     * @param string $original
     */
    public function alias($id, $original)
    {
        $this->instances[$id] = $this->get($original);

        return $this;
    }

    /**
     * Resolves the specified parameters from a container.
     *
     * @param  \ReflectionFunction|\ReflectionMethod $reflector
     * @param  array                                 $parameters
     * @return array
     */
    public function arguments($reflector, $parameters = array())
    {
        $arguments = array();

        foreach ($reflector->getParameters() as $key => $parameter) {
            $argument = $this->argument($parameter);

            $name = $parameter->getName();

            $arguments[$key] = $argument ?: $parameters[$name];
        }

        return $arguments;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @param  string $id
     * @return mixed
     */
    public function get($id)
    {
        if (! $this->has($id)) {
            $message = 'Alias (%s) is not being managed by the container';

            throw new Exception\NotFoundException(sprintf($message, $id));
        }

        $entry = isset($this->instances[$id]) ? $this->instances[$id] : $this->resolve($id);

        if (! is_object($entry)) {
            $message = 'Alias (%s) is not an object';

            throw new Exception\ContainerException(sprintf($message, $id));
        }

        return $entry;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        return isset($this->instances[$id]) || $this->extra->has($id);
    }

    /**
     * Resolves the specified identifier to an instance.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @param  string                                        $id
     * @param  \Psr\Http\Message\ServerRequestInterface|null $request
     * @return mixed
     */
    public function resolve($id, ServerRequestInterface $request = null)
    {
        $reflection = new \ReflectionClass($id);

        if ($constructor = $reflection->getConstructor()) {
            $arguments = array();

            foreach ($constructor->getParameters() as $parameter) {
                $argument = $this->argument($parameter);

                array_push($arguments, $this->request($argument, $request));
            }

            return $reflection->newInstanceArgs($arguments);
        }

        return $this->extra->get($id);
    }

    /**
     * Sets a new instance to the container.
     *
     * @param  string $id
     * @param  mixed  $concrete
     * @return self
     */
    public function set($id, $concrete)
    {
        $this->instances[$id] = $concrete;

        return $this;
    }

    /**
     * Returns an argument based on the given parameter.
     *
     * @param  \ReflectionParameter $parameter
     * @return mixed|null
     */
    protected function argument(\ReflectionParameter $parameter)
    {
        $argument = null;

        try {
            $argument = $parameter->getDefaultValue();
        } catch (\ReflectionException $exception) {
            $class = $parameter->getClass();

            $name = $class ? $class->getName() : $parameter->getName();

            $argument = $this->value($name);
        }

        return $argument;
    }

    /**
     * Returns the manipulated ServerRequest (from middleware) to an argument.
     *
     * @param  mixed                                         $argument
     * @param  \Psr\Http\Message\ServerRequestInterface|null $request
     * @return mixed
     */
    protected function request($argument, ServerRequestInterface $request = null)
    {
        ! $argument instanceof ServerRequestInterface || $argument = $request ?: $argument;

        return $argument;
    }

    /**
     * Returns the value of the specified argument.
     *
     * @param  string $name
     * @return mixed|null
     */
    protected function value($name)
    {
        $extra = $this->extra;

        $object = isset($this->instances[$name]) ? $this->get($name) : null;

        return ! $object && $extra->has($name) ? $extra->get($name) : $object;
    }
}
