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
use Illuminate\Container\Container;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;

class LaravelBinderAdapterTest extends PHPUnit_Framework_TestCase
{
    /** @var  ContainerInterface */
    private $container;
    /** @var  Binder */
    private $binder;

    protected function setUp()
    {
        parent::setUp();
        $laravelContainer = new Container();
        $this->container = new LaravelContainerAdapter($laravelContainer);
        $this->binder = new LaravelBinderAdapter($laravelContainer);
    }


    public function testBindMethod()
    {
        $this->binder->bind('Cliphar\Container\Laravel\SomeInterface', 'Cliphar\Container\Laravel\SomeImplementation');

        $resolved = $this->container->get('Cliphar\Container\Laravel\SomeInterface');
        $secondResolution = $this->container->get('Cliphar\Container\Laravel\SomeInterface');

        $this->assertInstanceOf('Cliphar\Container\Laravel\SomeInterface', $resolved);
        $this->assertInstanceOf('Cliphar\Container\Laravel\SomeImplementation', $resolved);
        $this->assertNotSame($resolved, $secondResolution);
    }

    public function testBindMethodReturnsDifferentInstances()
    {
        $instance = new SomeImplementation();
        $this->binder->bind('Cliphar\Container\Laravel\SomeInterface', 'Cliphar\Container\Laravel\SomeImplementation');

        $resolved = $this->container->get('Cliphar\Container\Laravel\SomeInterface');

        $this->assertInstanceOf('Cliphar\Container\Laravel\SomeInterface', $resolved);
        $this->assertInstanceOf('Cliphar\Container\Laravel\SomeImplementation', $resolved);
    }

    public function testBindInstanceMethod()
    {
        $laravelContainer = new Container();
        $this->container = new LaravelContainerAdapter($laravelContainer);
        $this->binder = new LaravelBinderAdapter($laravelContainer);
        $instance = new SomeImplementation();
        $this->binder->bindToInstance('Cliphar\Container\Laravel\SomeInterface', $instance);

        $resolved = $this->container->get('Cliphar\Container\Laravel\SomeInterface');
        $secondResolution = $this->container->get('Cliphar\Container\Laravel\SomeInterface');

        $this->assertInstanceOf('Cliphar\Container\Laravel\SomeInterface', $resolved);
        $this->assertInstanceOf('Cliphar\Container\Laravel\SomeImplementation', $resolved);
        $this->assertSame($resolved, $secondResolution);
    }

}

interface SomeInterface
{
}
class SomeImplementation implements SomeInterface
{
}