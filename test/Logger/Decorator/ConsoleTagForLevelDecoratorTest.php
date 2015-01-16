<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Logger\Decorator;

use Cliphar\Logger\ConsoleLogger;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleTagForLevelDecoratorTest extends \PHPUnit_Framework_TestCase
{
    private $output;

    protected function setUp()
    {
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
    }

    public function testTagIsWritten()
    {
        $this->givenTheHighestVerbosity();
        $tagLevelMap = array(LogLevel::DEBUG => "THETAG");
        $levelDecorator = new ConsoleTagForLevelDecorator(new ConsoleMessageDecorator(), $tagLevelMap);


        $this->expectsMessageIsWritten("<THETAG>hola</THETAG>");

        $consoleLogger = new ConsoleLogger($this->output, LogLevel::WARNING, array(), $levelDecorator);
        $consoleLogger->debug("hola");
    }

    public function testFalseTagIsNotWritten()
    {
        $this->givenTheHighestVerbosity();
        $tagLevelMap = array(LogLevel::INFO => false);
        $levelDecorator = new ConsoleTagForLevelDecorator(new ConsoleMessageDecorator(), $tagLevelMap);


        $this->expectsMessageIsWritten("hola");

        $consoleLogger = new ConsoleLogger($this->output, LogLevel::WARNING, array(), $levelDecorator);
        $consoleLogger->info("hola");
    }

    private function givenTheHighestVerbosity()
    {
        $this->output->expects($this->any())
            ->method("getVerbosity")
            ->will($this->returnValue(OutputInterface::VERBOSITY_DEBUG));
    }

    private function expectsMessageIsWritten($message)
    {
        $this->output->expects($this->once())
            ->method("writeln")
            ->with($message);
    }
}