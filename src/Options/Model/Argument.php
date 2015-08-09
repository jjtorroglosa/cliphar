<?php
/**
 * Created by IntelliJ IDEA.
 * User: juanjo
 * Date: 9/8/15
 * Time: 21:48
 */

namespace Cliphar\Options\Model;


class Argument
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var boolean
     */
    private $required;

    /**
     * @var string
     */
    private $defaultValue;

    /**
     * Argument constructor.
     * @param string $name
     * @param bool $required
     * @param string $defaultValue
     */
    public function __construct($name, $required, $defaultValue)
    {
        $this->name = $name;
        $this->required = $required;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function hasDefaultValue()
    {
        return $this->defaultValue !== "";
    }
}