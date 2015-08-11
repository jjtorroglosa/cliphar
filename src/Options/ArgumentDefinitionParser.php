<?php
/**
 * Created by IntelliJ IDEA.
 * User: juanjo
 * Date: 9/8/15
 * Time: 21:42
 */

namespace Cliphar\Options;


use Cliphar\Options\Exception\OptionsParsingException;
use Cliphar\Options\Model\Argument;
use Cliphar\Options\Model\Option;

class ArgumentDefinitionParser
{
    /**
     * @param $string
     * @return Argument
     * @throws OptionsParsingException
     */
    public function parse($string)
    {
        $optionalRegex = '(?<optional>[\?])';
        $defaultValueRegex = '[=](?<default>[A-Za-z0-9\-\_]+|"[^"]+")';
        $regex = '/\<(?<name>[a-zA-Z\_\-]+)\>(?:' . $optionalRegex . '|' . $defaultValueRegex . ')?(?:\s|$)/';

        $result = preg_match($regex, $string, $matches);

        var_dump($matches, $result);

        if (!$result) {
            throw new OptionsParsingException("Error parsing the options definition");
        }

        $name = $matches['name'];
        $isRequired = $this->isRequired($matches);
        $defaultValue = $this->getDefaultValue($matches);

        return new Argument($name, $isRequired, $defaultValue);
    }

    /**
     * @param $matches
     * @return bool
     * @throws OptionsParsingException
     */
    private function isRequired($matches)
    {
        if (!isset($matches['optional'])) {
            return true;
        }

        switch ($matches['optional']) {
            case "":
                return true;
                break;
            case "?":
                return false;
                break;
            default:
                throw new OptionsParsingException("Unexpected character found. None or '?' expected");
        }
    }

    /**
     * @param $matches
     * @return string
     */
    private function getDefaultValue($matches)
    {
        $defaultValue = isset($matches['default']) ? $matches['default'] : "";
        return strtr($defaultValue, array("\"" => ""));
    }
}