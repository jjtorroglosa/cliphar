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

use Cliphar\Command\CommandFactory;
use Cliphar\Container\ContainerFactory;
use Cliphar\Exception\InvalidCommandException;
use Cliphar\Exception\InvalidServiceProviderException;
use Cliphar\ServiceProvider;
use Cliphar\Symfony\SymfonyConsoleApplication;
use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Class BaseApplication
 */
abstract class BaseApplication
{
    private static $instance;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Binder
     */
    protected $binder;

    /**
     * @var SymfonyConsoleApplication
     */
    private $symfonyApplication;

    /**
     * @var Command
     */
    private $commands;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    protected final function __construct()
    {
        $factory = new ContainerFactory();
        $this->container = $factory->createLaravelContainer();
        $this->binder = $this->container->get('Cliphar\Binder');
        $this->symfonyApplication = new SymfonyConsoleApplication($this->getName(), $this->getVersion(), $this->binder);
        $this->commandFactory = new CommandFactory($this->container);
    }

    /**
     * Runs the application
     * @throws \Exception
     */
    public final function run()
    {
        $this->symfonyApplication->registerIO();

        $this->registerServices();
        $this->registerCommands();

        $this->symfonyApplication->run();
    }

    public function getContainer()
    {
        return $this->container;
    }

    private function registerServices()
    {
        foreach ($this->getProviders() as $p) {
            /** @var ServiceProvider $provider */
            $provider = $this->resolveServiceProvider($p);

            $provider->register();
        }
    }

    private function registerCommands()
    {
        foreach ($this->getCommands() as $c) {
            /** @var Command $command */
            $command = $this->resolveCommand($c);

            $this->symfonyApplication->add($command);
        }
    }

    /**
     * @param string|ServiceProvider $provider
     * @return ServiceProvider
     * @throws InvalidServiceProviderException
     */
    private function resolveServiceProvider($provider)
    {
        if (is_string($provider)) {
            $provider = $this->container->get($provider);
        }

        if (! ($provider instanceof ServiceProvider)) {
            throw new InvalidServiceProviderException();
        }

        return $provider;
    }

    /**
     * @param $command
     * @return Command
     * @throws InvalidCommandException
     */
    private function resolveCommand($command)
    {
        if (is_string($command)) {
            $command = $this->container->get($command);
        }

        if (!($command instanceof Command)) {
            throw new InvalidCommandException();
        }

        return $command;
    }

    /**
     * @return string[]
     */
    protected function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param $name
     * @param $definition
     * @param $callable
     */
    public function addCommand($name, $definition, $callable)
    {
        $command = $this->commandFactory->createCommand($name, $definition, $callable);
        $this->commands[$command->getName()] = $command;
    }

    /**
     * @return string[]
     */
    abstract protected function getProviders();

    /**
     * @return string
     */
    abstract protected function getVersion();

    /**
     * @return string
     */
    abstract protected function getName();
}