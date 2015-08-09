<?php
/**
 * Created by IntelliJ IDEA.
 * User: juanjo
 * Date: 9/8/15
 * Time: 20:17
 */

namespace Cliphar\Options;

use Cliphar\Options\Exception\OptionsParsingException;
use Cliphar\Options\Model\Option;
use Symfony\Component\Yaml\Exception\ParseException;

class OptParser
{
    /**
     * @param string $string
     * @return Model\Option[]
     * @throws OptionsParsingException
     */
    public function parse($string)
    {
        $shortFormRegex= '(\|(?<shortForm>[a-zA-Z]))?';
        $optionalRegex = '(?<optional>[\?])';
        $defaultValueRegex = '[\=](?<default>[^\s]+|\"[A-Za-z0-9\s]+\")';
        $regex = '/^\[(?<name>[a-zA-Z\_\-]+)'.$shortFormRegex.'\]('.$optionalRegex.'|'.$defaultValueRegex.')?$/';

        $result = preg_match($regex, $string, $matches);

        if (!$result) {
            throw new OptionsParsingException("Error parsing the options definition");
        }

        $name = $matches['name'];
        $isRequired = $this->isRequired($matches);
        $shortForm = isset($matches['shortForm']) ? $matches['shortForm'] : "";
        $defaultValue = $this->getDefaultValue($matches);
        return array($name => new Option($isRequired, true, $name, $shortForm, $defaultValue));
    }

    /**
     * @param $matches
     * @return bool
     */
    private function isRequired($matches)
    {
        if (! isset($matches['optional'])) {
            return true;
        }
        switch ($matches['optional']) {
            case "":
                $isRequired = true;
                break;
            case "?":
                $isRequired = false;
                break;
            default:
                throw new OptionsParsingException("Unexpected character found");
        }
        return $isRequired;
    }

    /**
     * @param $matches
     * @return string
     */
    private function getDefaultValue($matches)
    {
        $defaultValue = isset($matches['default']) ? $matches['default'] : "";
        return strtr($defaultValue, array("\""=> ""));
    }
}