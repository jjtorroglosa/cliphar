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

/**
 * Interface Binder
 */
interface Binder
{
    /**
     * @param string $abstract
     * @param string $concrete
     * @return mixed
     */
    public function bind($abstract, $concrete);

    /**
     * @param string $abstract
     * @param mixed $instance
     * @return mixed
     */
    public function bindToInstance($abstract, $instance);
}