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
use Cliphar\Container\Exception\ContainerException;
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
     * @inheritdoc
     */
    public function bind($abstract, $concrete)
    {
        if ($concrete instanceof \Closure || is_string($concrete)) {
            $this->container->bind($abstract, $concrete);
        } else {
            throw new ContainerException("Invalid parameter. This method only accepts Closure or string for \$to parameter");
        }
    }

    /**
     * @inheritdoc
     */
    public function bindSingleton($abstract, $concrete)
    {
        if ($concrete instanceof \Closure) {
            $this->container->bindShared($abstract, $concrete);
        } else if (is_object($concrete)) {
            $this->container->instance($abstract, $concrete);
        } else if (is_string($concrete)) {
            $this->container->singleton($abstract, $concrete);
        } else {
            throw new ContainerException("Invalid parameter. This method only accepts Closure, string or object \$to parameter");
        }
    }

    /**
     * @inheritdoc
     */
    public function bindToInstance($abstract, $instance)
    {
        $this->container->instance($abstract, $instance);
    }
}