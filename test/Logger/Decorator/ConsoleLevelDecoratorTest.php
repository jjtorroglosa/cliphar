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

class ConsoleLevelDecoratorTest extends \PHPUnit_Framework_TestCase
{

    private $output;

    protected function setUp()
    {
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
    }


    public function testLevelIsPrinted()
    {
        $levelDecorator = new ConsoleLevelDecorator(new ConsoleMessageDecorator());

        $this->givenTheMaxVerbosity();

        $this->expectsMessageIsWritten("[ERROR] hola");

        $consoleLogger = new ConsoleLogger($this->output, LogLevel::WARNING, array(), $levelDecorator);
        $consoleLogger->error("hola");
    }

    private function givenTheMaxVerbosity()
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