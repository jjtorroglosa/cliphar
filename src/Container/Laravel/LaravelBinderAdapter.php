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

use Cliphar\Binder;
use Illuminate\Container\Container as LaravelContainer;


/**
 * Class LaravelBinderAdapter 
 */
class LaravelBinderAdapter implements Binder
{
    /**
     * @var LaravelContainer
     */
    private $container;

    public function __construct(LaravelContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $abstract
     * @param string $concrete
     */
    public function bind($abstract, $concrete)
    {
        $this->container->bind($abstract, $concrete);
    }

    /**
     * @param string $abstract
     * @param mixed $instance
     */
    public function bindToInstance($abstract, $instance)
    {
        $this->container->instance($abstract, $instance);
    }

    public function alias($abstract, $alias)
    {
        $this->container->alias($abstract, $alias);
    }
}