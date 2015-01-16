<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Container\Laravel;

use Cliphar\Container\Exception\ContainerException;
use Cliphar\Container\Exception\NotFoundException;
use Illuminate\Container\Container as LaravelContainerInterface;
use Interop\Container\ContainerInterface as AcclimateContainerInterface;

/**
 * An adapter from a Laravel Container to the standardized ContainerInterface
 */
class LaravelContainerAdapter implements AcclimateContainerInterface
{
    /**
     * @var LaravelContainerInterface A Laravel Container
     */
    private $container;

    /**
     * @param LaravelContainerInterface $container A Laravel Container
     */
    public function __construct(LaravelContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        if ($this->has($id)) {
            try {
                return $this->container->make($id);
            } catch (\Exception $prev) {
                throw new ContainerException(sprintf("An exception occurred trying to resolve %s, see previous", $id), 0, $prev);
            }
        } else {
            throw new NotFoundException(sprintf('There is no entry found in the container for %s and it is not an instantiable class', $id));
        }
    }

    public function has($id)
    {
        return $this->container->bound($id) || class_exists($id);
    }
}