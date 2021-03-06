<?php

namespace Rougin\Slytherin\Container\Exception;

/**
 * Not Found Exception
 *
 * A specified exception in handling errors in containers.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class NotFoundException extends \InvalidArgumentException implements \Psr\Container\NotFoundExceptionInterface
{
}
