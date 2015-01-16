<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Logger;

use Cliphar\Logger\Decorator\ConsoleLevelDecorator;
use Cliphar\Logger\Decorator\ConsoleMessageDecorator;
use Cliphar\Logger\Decorator\ConsoleTagForLevelDecorator;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleLoggerTest extends \PHPUnit_Framework_TestCase
{
    private $consoleLogger;

    const A_MESSAGE = "A message";

    private $consoleOutput;

    protected function setUp()
    {
        $this->consoleOutput = $this->getMock('Symfony\Component\Console\Output\ConsoleOutput');
    }


    public function testPrintToStderr()
    {
        $this->givenThatVerbosityIs(OutputInterface::VERBOSITY_VERY_VERBOSE);
        $errorOutput = $this->getMock('Symfony\Component\Console\Output\ConsoleOutput');
        $this->consoleOutput->expects($this->once())
            ->method("getErrorOutput")
            ->will($this->returnValue($errorOutput));

        $this->consoleOutput->expects($this->never())
            ->method("writeln");

        $errorOutput->expects($this->once())
            ->method("getVerbosity")
            ->will($this->returnValue(OutputInterface::VERBOSITY_VERY_VERBOSE));

        $errorOutput->expects($this->once())
            ->method("writeln")
            ->with(self::A_MESSAGE);

        $this->consoleLogger = new ConsoleLogger($this->consoleOutput);
        $this->consoleLogger->error(self::A_MESSAGE);
    }

    public function testPrintToStdout()
    {
        $this->givenThatVerbosityIs(ConsoleOutput::VERBOSITY_VERY_VERBOSE);
        $this->consoleOutput->expects($this->never())
            ->method("getErrorOutput");
        $this->consoleOutput->expects($this->once())
            ->method("writeln")
            ->with(self::A_MESSAGE);

        $this->consoleLogger = new ConsoleLogger($this->consoleOutput);
        $this->consoleLogger->info(self::A_MESSAGE);
    }

    public function testMessageIsNotWrittenIfLessVerbosity()
    {
        $this->givenThatVerbosityIs(ConsoleOutput::VERBOSITY_VERBOSE);
        $this->consoleOutput->expects($this->never())
            ->method("getErrorOutput");
        $this->consoleOutput->expects($this->never())
            ->method("writeln");

        $this->consoleLogger = new ConsoleLogger($this->consoleOutput);
        $this->consoleLogger->info(self::A_MESSAGE);
    }

    public function testLogLevelAndTag()
    {
        $this->consoleOutput->expects($this->once())
            ->method("writeln")
            ->with(sprintf("<fg=cyan>[DEBUG] %s</fg=cyan>", self::A_MESSAGE));
        $this->givenThatVerbosityIs(ConsoleOutput::VERBOSITY_DEBUG);

        $decorator = new ConsoleTagForLevelDecorator(new ConsoleLevelDecorator(new ConsoleMessageDecorator()));
        $this->consoleLogger = new ConsoleLogger($this->consoleOutput, LogLevel::WARNING, array(), $decorator);
        $this->consoleLogger->debug(self::A_MESSAGE);
    }

    public function testInterpolation()
    {
        $this->consoleOutput->expects($this->once())
            ->method("writeln")
            ->with(sprintf("%syes!", self::A_MESSAGE));
        $this->givenThatVerbosityIs(ConsoleOutput::VERBOSITY_DEBUG);

        $this->consoleLogger = new ConsoleLogger($this->consoleOutput, LogLevel::WARNING);
        $this->consoleLogger->debug(self::A_MESSAGE . "{INTERPOLATED}", array("INTERPOLATED" => "yes!"));
    }

    private function givenThatVerbosityIs($verbosity)
    {
        $this->consoleOutput->expects($this->any())
            ->method("getVerbosity")
            ->will($this->returnValue($verbosity));
    }
}