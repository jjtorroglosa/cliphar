<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Command;

use Cliphar\Command\Fixtures\AnyDependency;
use Cliphar\InputDefinition\Model\Argument;
use Cliphar\InputDefinition\Model\InputDefinition;
use Cliphar\InputDefinition\Model\Option;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;

class CommandFactoryUnitTest extends PHPUnit_Framework_TestCase
{
    const NO_DEFAULT_VALUE = null;
    const IS_REQUIRED = true;
    const IS_NOT_REQUIRED = false;
    /** @var CommandFactory */
    private $factory;
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $container;
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $parser;

    protected function setUp()
    {
        parent::setUp();
        $this->container = $this->getMock('Interop\Container\ContainerInterface');
        $this->parser = $this->getMock('Cliphar\InputDefinition\InputDefinitionParser');
        $this->factory = new CommandFactory($this->container, $this->parser);
    }


    public function testSimpleCommand()
    {
        $this->givenAnEmptyInputDefinition();
        $called = false;
        $code = function () use (& $called) {
            $called = true;
        };

        $command = $this->factory->createCommand("simple", "whatever", $code);
        $command->run(new ArrayInput(array()), new NullOutput());

        $this->assertEquals("simple", $command->getName());
        $this->assertCount(0, $command->getDefinition()->getArguments());
        $this->assertCount(0, $command->getDefinition()->getOptions());
        $this->assertTrue($called);
    }

    private function givenAnEmptyInputDefinition()
    {
        $this->parser->expects($this->any())
            ->method('parse')
            ->will($this->returnValue(new InputDefinition()));
    }

    public function testOptionsArePassed()
    {
        $this->givenThatParserReturnsADefinitionWithOption(
            new Option(self::IS_REQUIRED, "option", "o", "defaultValue")
        );

        $command = $this->factory->createCommand("optionable", "whatever", function () {});
        $option = $command->getDefinition()->getOption('option');

        $this->assertEquals("optionable", $command->getName());
        $this->assertCount(1, $command->getDefinition()->getOptions());
        $this->assertTrue($option->isValueRequired(), "isRequired should be true, as it has a default value");
        $this->assertEquals("defaultValue", $option->getDefault());
        $this->assertEquals("o", $option->getShortcut());
    }

    public function testValueIsNotRequiredIfNoDefaultValue()
    {
        $this->givenThatParserReturnsADefinitionWithOption(
            new Option(self::IS_REQUIRED, "option", "o", self::NO_DEFAULT_VALUE)
        );

        $command = $this->factory->createCommand("optionable", "whatever", function () {});
        $option = $command->getDefinition()->getOption('option');

        $this->assertFalse($option->isValueRequired(), "isRequired should be false, as it hasn't a default value");
    }

    /**
     * @param $option
     */
    private function givenThatParserReturnsADefinitionWithOption($option)
    {
        $inputDefinition = new InputDefinition();
        $inputDefinition->addOption($option);
        $this->parser->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($inputDefinition));
    }

    public function testArgumentsArePassed()
    {
        $this->givenThatParserReturnsADefinitionWithArgument(
            new Argument("argument", self::IS_REQUIRED, "defaultValue")
        );

        $command = $this->factory->createCommand("with_arguments", "whatever", function () {});
        $option = $command->getDefinition()->getArgument('argument');

        $this->assertEquals("argument", $option->getName());
        $this->assertCount(1, $command->getDefinition()->getArguments());
        $this->assertFalse($option->isRequired(), "Argument should not be required, as it has a default value");
        $this->assertEquals("defaultValue", $option->getDefault());
    }

    public function testArgumentsWithoutDefaultValueIsRequired()
    {
        $this->givenThatParserReturnsADefinitionWithArgument(
            new Argument("argument", self::IS_REQUIRED, self::NO_DEFAULT_VALUE)
        );

        $command = $this->factory->createCommand("with_arguments", "whatever", function () {});
        $option = $command->getDefinition()->getArgument('argument');

        $this->assertTrue($option->isRequired(), "Argument should be required, as it hasn't a default value and is marked as required");
        $this->assertNull($option->getDefault());
    }

    public function testArgumentsWithoutDefaultValueCanBeOptional()
    {
        $this->givenThatParserReturnsADefinitionWithArgument(
            new Argument("argument", self::IS_NOT_REQUIRED, self::NO_DEFAULT_VALUE)
        );

        $command = $this->factory->createCommand("with_arguments", "whatever", function () {});
        $option = $command->getDefinition()->getArgument('argument');

        $this->assertFalse($option->isRequired(), "Argument should be required, as it hasn't a default value and is marked as required");
    }

    public function testThatInputInterfaceIsPassedIfDeclaredInClosure()
    {
        $this->givenAnEmptyInputDefinition();
        $inputInterface = new ArrayInput(array());
        $outputInterface = new NullOutput();
        $passedParameter = null;
        $code = function (InputInterface $inputInterface) use (& $passedParameter) {
            $passedParameter = $inputInterface;
        };

        $command = $this->factory->createCommand("simple", "whatever", $code);
        $command->run($inputInterface, $outputInterface);

        $this->assertSame($inputInterface, $passedParameter);
    }

    public function testThatClosureParametersAreResolvedByContainer()
    {
        $this->givenAnEmptyInputDefinition();
        $inputInterface = new ArrayInput(array());
        $outputInterface = new NullOutput();
        $dependencyPassed = null;
        $inputPassed = null;
        $code = function (InputInterface $inputInterface, AnyDependency $dependency) use (&$inputPassed, & $dependencyPassed) {
            $inputPassed = $inputInterface;
            $dependencyPassed = $dependency;
        };

        $expectedDependency = new AnyDependency();
        $this->expectsContainerIsCalledAndReturns($expectedDependency);

        $command = $this->factory->createCommand("simple", "whatever", $code);
        $command->run($inputInterface, $outputInterface);

        $this->assertSame($expectedDependency, $dependencyPassed);
        $this->assertSame($inputPassed, $inputInterface);
    }

    private function givenThatParserReturnsADefinitionWithArgument($argument)
    {
        $inputDefinition = new InputDefinition();
        $inputDefinition->addArgument($argument);
        $this->parser->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($inputDefinition));
    }

    private function expectsContainerIsCalledAndReturns($dependency)
    {
        $this->container->expects($this->once())
            ->method('get')
            ->with('Cliphar\Command\Fixtures\AnyDependency')
            ->will($this->returnValue($dependency));
    }
}