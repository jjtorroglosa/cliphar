<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Container;

use Cliphar\Container\Laravel\LaravelBinderAdapter;
use Cliphar\Container\Laravel\LaravelContainerAdapter;
use Interop\Container\ContainerInterface;
use Illuminate\Container\Container as LaravelContainer;

/**
 * Class ContainerFactory
 */
class ContainerFactory
{
    /**
     * @return ContainerInterface
     */
    public function createLaravelContainer()
    {
        $laravelContainer = new LaravelContainer();
        $interopContainer = new LaravelContainerAdapter($laravelContainer);
        $binder = new LaravelBinderAdapter($laravelContainer);
        $binder->bindToInstance('Interop\Container\ContainerInterface', $interopContainer);
        $binder->bindToInstance('Cliphar\Binder', $binder);

        return $interopContainer;
    }
}