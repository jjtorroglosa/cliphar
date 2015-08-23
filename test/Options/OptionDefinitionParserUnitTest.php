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

class OptionDefinitionParserUnitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var OptionDefinitionParser
     */
    private $optParser;

    protected function setUp()
    {
        parent::setUp();
        $this->optParser = new OptionDefinitionParser();
    }

    public function testRequiredOption()
    {
        $string = "[option]";

        $option = $this->optParser->parse($string);

        $this->assertTrue($option->isRequired());
    }

    public function testNotRequiredOption()
    {
        $string = "[option]?";

        $option = $this->optParser->parse($string);

        $this->assertFalse($option->isRequired());
        $this->assertEquals("", $option->getShortForm());
    }

    public function testShortOption()
    {
        $string = "[option|o]";

        $option = $this->optParser->parse($string);

        $this->assertEquals("o", $option->getShortForm());
    }

    public function testOptionWithValue()
    {
        $string = "[option|o]=default";

        $option = $this->optParser->parse($string);

        $this->assertEquals("default", $option->getDefaultValue());
    }

    public function testOptionWithValueWithSpaces()
    {
        $string = '[option|o]="default values  with spaces 2"';

        $option = $this->optParser->parse($string);

        $this->assertEquals("default values  with spaces 2", $option->getDefaultValue());
    }

    public function testOptionWithValueAndOptionalIsError()
    {
        $string = "[option|o]?=default";

        $this->setExpectedException('\Cliphar\Options\Exception\OptionsParsingException');

        $this->optParser->parse($string);
    }
}