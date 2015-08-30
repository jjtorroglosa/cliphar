<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar;

use Closure;
use Interop\Container\Exception\ContainerException;

/**
 * Interface Binder
 */
interface Binder
{
    /**
     * @param string $abstract
     * @param \Closure|string $concrete
     * @throws ContainerException
     */
    public function bind($abstract, $concrete);

    /**
     * @deprecated
     * @param string $abstract
     * @param mixed $instance
     */
    public function bindToInstance($abstract, $instance);

    /**
     * @param $abstract
     * @param \Closure|object|string $concrete
     * @throws ContainerException
     */
    public function bindSingleton($abstract, $concrete);
}