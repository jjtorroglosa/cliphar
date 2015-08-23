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
        $optionRegex = '(?<option>\[[^\s]+\]|\[[^\s]+\]="[^"]+")';
        $argumentRegex = '(?<argument>\<[^\s](^\"]+)\")';

        $regex = '/^('.$optionRegex.')$/';
        $result = preg_match($regex, $string, $matches);
        if (!$result) {
            throw new OptionsParsingException("Invalid opt string");
        }

        $option = $this->optionParser->parse($matches["option"]);

        return array($option->getName() => $option);
    }
}