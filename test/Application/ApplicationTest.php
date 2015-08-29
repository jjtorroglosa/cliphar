<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Application;

use Cliphar\Application\Fixtures\TestApplication;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Input\InputInterface;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testApplication()
    {
        $_SERVER['argv'] = array('TestApplication','anycommand', '-o', 'argumentpassed');
        $app = TestApplication::getInstance();
        $this->givenACommandDefinition($option, $argument);

        $exitCode = $app->run();

        $this->assertTrue($option);
        $this->assertEquals('argumentpassed', $argument);
        $this->assertEquals(0, $exitCode);
    }

    private function givenACommandDefinition(& $option, & $argument)
    {
        cmd("anycommand", '[option|o] <argument>="Value"',

            function (InputInterface $inputInterface) use (& $option, & $argument) {
                $option = $inputInterface->getOption('option');
                $argument = $inputInterface->getArgument('argument');
            }

        );
    }
}