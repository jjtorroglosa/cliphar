<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Options;

use PHPUnit_Framework_TestCase;

class ArgumentDefinitionParserUnitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ArgumentDefinitionParser
     */
    private $optParser;

    protected function setUp()
    {
        parent::setUp();
        $this->optParser = new ArgumentDefinitionParser();
    }

    public function testRequiredArgument()
    {
        $string = "<argument>";

        $argument = $this->optParser->parse($string);

        $this->assertTrue($argument->isRequired());
    }

    public function testNotRequiredArgument()
    {
        $string = "<argument>?";

        $argument = $this->optParser->parse($string);

        $this->assertFalse($argument->isRequired());
    }

    public function testArgumentWithValue()
    {
        $string = "<argument>=default";

        $argument = $this->optParser->parse($string);

        $this->assertEquals("default", $argument->getDefaultValue());
    }

    public function testArgumentWithValueWithSpaces()
    {
        $string = '<argument>="default #! values  with spaces 2"';

        $argument = $this->optParser->parse($string);

        $this->assertEquals("default #! values  with spaces 2", $argument->getDefaultValue());
    }

    public function testArgumentWithValueAndOptionalIsError()
    {
        $string = "<argument>?=default";

        $this->setExpectedException('\Cliphar\Options\Exception\OptionsParsingException');

        $argument = $this->optParser->parse($string);
    }

    public function testArgumentWithShortNameThrowsException()
    {
        $string = "<argument|o>?=default";

        $this->setExpectedException('\Cliphar\Options\Exception\OptionsParsingException');

        $argument = $this->optParser->parse($string);
    }

    public function testSeveral()
    {
        $string = "[option] <option> <argument> ";


        $argument = $this->optParser->parse($string);
    }
}