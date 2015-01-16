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


use PHPUnit_Framework_TestCase;

class ContainerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerFactory
     */
    private $factory;

    protected function setUp()
    {
        parent::setUp();
        $this->factory = new ContainerFactory();
    }

    public function testLaravelContainerIsRegistered()
    {
        $container = $this->factory->createLaravelContainer();
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $container);
        $resolvedContainer = $container->get('Interop\Container\ContainerInterface');
        $this->assertSame($container, $resolvedContainer);
        $this->assertInstanceOf('Cliphar\Binder', $container->get('Cliphar\Binder'));
    }
}