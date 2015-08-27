<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Symfony;

use Cliphar\Binder;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SymfonyConsoleApplication
 */
class SymfonyConsoleApplication extends Application
{
    /**
     * @var \Cliphar\Binder
     */
    private $binder;

    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN', Binder $binder)
    {
        parent::__construct($name, $version);
        $this->binder = $binder;
    }


    public function registerIO($input, $output)
    {
        parent::configureIO($input, $output);
        $this->binder->bindToInstance('Symfony\Component\Console\Output\OutputInterface', $output);
        $this->binder->bindToInstance('Symfony\Component\Console\Input\InputInterface', $input);
    }
}