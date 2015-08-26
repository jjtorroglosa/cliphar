<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\InputDefinition;

use Cliphar\InputDefinition\Model\Option;
use PHPUnit_Framework_TestCase;

class InputDefinitionParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var InputDefinitionParser
     */
    private $inputDefinitionParser;

    protected function setUp()
    {
        parent::setUp();
        $this->inputDefinitionParser = new InputDefinitionParser();
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testFirstCharacterShouldBeOpeningOptionOrArgument()
    {
        $definition = " <arg_name>";

        $argument = $this->inputDefinitionParser->parse($definition);
    }

    public function testNoParameters()
    {
        $definition = "";

        $inputDefinition = $this->inputDefinitionParser->parse($definition);

        $this->assertEmpty($inputDefinition->getArguments());
        $this->assertEmpty($inputDefinition->getOptions());
    }


    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testANameShouldBeEncounteredAfterOpenArgumentIndicator()
    {
        $definition = "< >";

        $this->inputDefinitionParser->parse($definition);
    }


    public function testSingleArgument()
    {
        $definition = "<arg_name>";

        $argument = $this->getFirstArgument($definition);

        $this->assertEquals("arg_name", $argument->getName());
        $this->assertTrue($argument->isRequired(), "Argument should be required");
        $this->assertEmpty($argument->getDefaultValue());
        $this->assertFalse($argument->hasDefaultValue(), "Argument shouldn't have a default value");
    }

    public function testNotRequiredArgument()
    {
        $definition   = "<not_required>?";

        $argument = $this->getFirstArgument($definition);

        $this->assertEquals("not_required", $argument->getName());
        $this->assertFalse($argument->isRequired());
        $this->assertEmpty($argument->getDefaultValue());
        $this->assertFalse($argument->hasDefaultValue());
    }

    public function testArgumentWithDefaultValue()
    {
        $definition = "<default_value>=\"default value\"";

        $argument = $this->getFirstArgument($definition);

        $this->assertEquals("default_value", $argument->getName());
        $this->assertFalse($argument->isRequired());
        $this->assertEquals("default value", $argument->getDefaultValue());
        $this->assertTrue($argument->hasDefaultValue());
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testThatAWhitespaceShouldBeEncounteredAfterDefaultValue()
    {
        $definition = "<argument>=\"any value\"<another_argument>";

        $this->inputDefinitionParser->parse($definition);
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testThatAStringShouldBeEncounteredAfterEqualsSign()
    {
        $definition = "<argument>=<argument>";

        $this->inputDefinitionParser->parse($definition);
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testInvalidModifierEncountered()
    {
        $definition = "<argument><argument>";

        $this->inputDefinitionParser->parse($definition);
    }

    public function testArgumentsList()
    {
        $definition = "<first> <second>";

        $arguments = $this->getArguments($definition);

        $this->assertCount(2, $arguments);
        $this->assertEquals("first", $arguments[0]->getName());
        $this->assertEquals("second", $arguments[1]->getName());
    }

    public function testOption()
    {
        $definition = "[any_option]";

        list($option) = $this->inputDefinitionParser->parse($definition)->getOptions();

        $this->assertEquals("any_option", $option->getName());
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testANameShouldBeEncounteredAfterOpenOptionIndicator()
    {
        $definition = "[ ]";

        $this->inputDefinitionParser->parse($definition);
    }

    public function testOptionWithAbbreviated()
    {
        $definition = "[any_option|o]";

        $option = $this->getFirstOption($definition);

        $this->assertEquals("any_option", $option->getName());
        $this->assertEquals("o", $option->getAbbreviatedName());
        $this->assertTrue($option->isRequired());
        $this->assertTrue($option->hasAbbreviatedName());
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testAnAbbreviatedNameShouldExistAfterDelimiter()
    {
        $definition = "[name| ]";

        $this->inputDefinitionParser->parse($definition);
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testOptionShouldBeClosedAfterAbbreviatedName()
    {
        $definition = "[name|n";

        $this->inputDefinitionParser->parse($definition);
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testOptionShouldBeClosedAfterNameIfNoAbbreviatedName()
    {
        $definition = "[name";

        $this->inputDefinitionParser->parse($definition);
    }

    public function testOptionNotRequried()
    {
        $definition = "[any_option|o]?";

        $option = $this->getFirstOption($definition);

        $this->assertFalse($option->isRequired());
    }

    public function testOptionWithDefaultValue()
    {
        $definition = "[any_option|o]=\"a value\"";

        $option = $this->getFirstOption($definition);

        $this->assertEquals("a value", $option->getDefaultValue());
        $this->assertTrue($option->hasDefaultValue());
    }

    public function testOptionsList()
    {
        $definition = "[first_option|o]=\"a value\" [second_option]";

        $options = $this->getOptions($definition);

        $this->assertEquals("first_option", $options[0]->getName());
        $this->assertEquals("second_option", $options[1]->getName());
    }

    public function testOptionsAndArguments()
    {
        $string = "[option|o]=\"a value\" <argument>";

        $inputDefinition = $this->inputDefinitionParser->parse($string);
        $options = $inputDefinition->getOptions();
        $arguments = $inputDefinition->getArguments();

        $this->assertCount(1, $options);
        $this->assertCount(1, $arguments);
        $this->assertEquals("option", $options[0]->getName());
        $this->assertEquals("argument", $arguments[0]->getName());
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testOptionsMustBeDefinedBeforeArguments()
    {
        $string = "<argument> [option]";

        $this->inputDefinitionParser->parse($string);
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testOptionError()
    {
        $definition = "[any_option|o]?=\"a value\"";
        $option = $this->getFirstOption($definition);

        $this->assertFalse($option->isRequired());
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    public function testParsingException()
    {
        $definition = "<default_value>?=\"default value\"";

        $argument = $this->getFirstArgument($definition);
    }

    /**
     * @param $definition
     * @return mixed
     */
    private function getFirstArgument($definition)
    {
        return @reset($this->getArguments($definition));
    }

    /**
     * @param $definition
     * @return \Cliphar\InputDefinition\Model\Argument[]
     */
    private function getArguments($definition)
    {
        return $this->inputDefinitionParser->parse($definition)->getArguments();
    }

    /**
     * @param $definition
     * @return Option
     * @throws \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    private function getFirstOption($definition)
    {
        return @reset($this->getOptions($definition));
    }

    /**
     * @param $definition
     * @return \Cliphar\InputDefinition\Model\Option[]
     * @throws \Cliphar\InputDefinition\Exception\InputDefinitionParsingException
     */
    private function getOptions($definition)
    {
        return $this->inputDefinitionParser->parse($definition)->getOptions();
    }
}