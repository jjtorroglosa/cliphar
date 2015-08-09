<?php
/**
 * Created by IntelliJ IDEA.
 * User: juanjo
 * Date: 9/8/15
 * Time: 20:18
 */

namespace Cliphar\Options;

use PHPUnit_Framework_TestCase;

class InputDefinitionParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var InputDefinitionParser
     */
    private $optParser;

    protected function setUp()
    {
        parent::setUp();
        $this->optParser = new InputDefinitionParser(new OptionDefinitionParser());
    }

    public function testRequiredOption()
    {
        $string = "[option]";

        $options = $this->optParser->parse($string);

        $this->assertArrayHasKey("option", $options);
        $this->assertTrue($options['option']->isRequired());
    }

    public function testNotRequiredOption()
    {
        $string = "[option]?";

        $options = $this->optParser->parse($string);

        $this->assertArrayHasKey("option", $options);
        $this->assertFalse($options['option']->isRequired());
        $this->assertEquals("", $options["option"]->getShortForm());
    }

    public function testShortOption()
    {
        $string = "[option|o]";

        $options = $this->optParser->parse($string);

        $this->assertArrayHasKey("option", $options);
        $this->assertEquals("o", $options["option"]->getShortForm());
    }

    public function testOptionWithValue()
    {
        $string = "[option|o]=default";

        $options = $this->optParser->parse($string);

        $this->assertArrayHasKey("option", $options);
        $this->assertEquals("default", $options["option"]->getDefaultValue());
    }

    public function testOptionWithValueWithSpaces()
    {
        $string = '[option|o]="default values  with spaces 2"';

        $options = $this->optParser->parse($string);

        $this->assertArrayHasKey("option", $options);
        $this->assertEquals("default values  with spaces 2", $options["option"]->getDefaultValue());
    }

    public function testOptionWithValueAndOptionalIsError()
    {
        $string = "[option|o]?=default";

        $this->setExpectedException('\Cliphar\Options\Exception\OptionsParsingException');

        $options = $this->optParser->parse($string);
    }
}