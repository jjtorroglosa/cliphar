<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */
/**
 *
 * @author Juan José Torroglosa Ramón
 */

namespace Cliphar\ServiceProvider;

use Cliphar\Binder;
use Cliphar\Logger\Decorator\ConsoleLevelDecorator;
use Cliphar\Logger\Decorator\ConsoleMessageDecorator;
use Cliphar\Logger\Decorator\ConsoleTagForLevelDecorator;
use Cliphar\ServiceProvider;
use Cliphar\Logger\ConsoleLogger;
use Interop\Container\ContainerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class LoggerProvider implements ServiceProvider
{
    const FORMAT_INFO = false;
    const FORMAT_NOTICE = 'fg=green';
    const FORMAT_ERROR = 'fg=red';
    const FORMAT_DEBUG = 'fg=cyan';
    const FORMAT_WARNING = 'fg=yellow';

    protected $binder;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(Binder $binder, ContainerInterface $container)
    {
        $this->binder = $binder;
        $this->container = $container;
    }

    public function register()
    {
        $this->binder->bind('Psr\Log\LoggerInterface', function() {
            $formatLevelMap = array(
                LogLevel::EMERGENCY => self::FORMAT_ERROR,
                LogLevel::ALERT => self::FORMAT_ERROR,
                LogLevel::CRITICAL => self::FORMAT_ERROR,
                LogLevel::ERROR => self::FORMAT_ERROR,
                LogLevel::WARNING => self::FORMAT_WARNING,
                LogLevel::NOTICE => self::FORMAT_NOTICE,
                LogLevel::INFO => self::FORMAT_INFO,
                LogLevel::DEBUG => self::FORMAT_DEBUG,
            );

            $verbosityLevelMap = array(
                LogLevel::EMERGENCY => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::ALERT => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::CRITICAL => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::ERROR => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::WARNING => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::INFO => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::DEBUG => OutputInterface::VERBOSITY_VERBOSE,
            );

            $output = $this->getOutput();

            $decorator = new ConsoleMessageDecorator();
            $decorator = new ConsoleLevelDecorator($decorator);

            if ($output->isDecorated()) {
                $decorator = new ConsoleTagForLevelDecorator($decorator, $formatLevelMap);
            }

            return new ConsoleLogger($output, LogLevel::WARNING, $verbosityLevelMap, $decorator);
        });
    }

    /**
     * @return ConsoleOutput
     */
    public function getOutput()
    {
        return $this->container->get('Symfony\Component\Console\Output\OutputInterface');
    }
}