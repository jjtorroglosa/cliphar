<?php
/**
 * Created by IntelliJ IDEA.
 * User: juanjo
 * Date: 9/8/15
 * Time: 20:17
 */

namespace Cliphar\Options;

use Cliphar\Options\Exception\OptionsParsingException;

class InputDefinitionParser
{
    /**
     * @var OptionDefinitionParser
     */
    private $optionParser;
    /**
     * @var
     */
    private $argumentParser;

    /**
     * InputDefinitionParser constructor.
     * @param OptionDefinitionParser $optionParser
     * @param ArgumentDefinitionParser $argumentParser
     */
    public function __construct(OptionDefinitionParser $optionParser, ArgumentDefinitionParser $argumentParser)
    {
        $this->optionParser = $optionParser;
        $this->argumentParser = $argumentParser;
    }

    /**
     * @param string $string
     * @return Model\Option[]
     * @throws OptionsParsingException
     */
    public function parse($string)
    {
        $optionRegex = '(?<option>\[[^\s]([^\"])+\")';
        $argumentRegex = '(?<argument>\<[^\s](^\"]+)\")';

        $regex = '/('.$optionRegex.'[\s]*)*('.$argumentRegex.'[\s]*)*/';

        $option = $this->optionParser->parseOption($string);
        return array($option->getName() => $option);
    }
}