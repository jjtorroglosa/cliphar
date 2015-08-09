<?php
/**
 * Created by IntelliJ IDEA.
 * User: juanjo
 * Date: 9/8/15
 * Time: 20:19
 */

namespace Cliphar\Options\Model;


class Option
{
    /**
     * @var bool
     */
    private $isRequired;
    /**
     * @var bool
     */
    private $hasValue;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $shortForm;
    /**
     * @var string
     */
    private $defaultValue;


    /**
     * Option constructor.
     * @param boolean $isRequired
     * @param boolean $hasValue
     * @param string $name
     * @param string $shortForm
     * @param string $defaultValue
     */
    public function __construct($isRequired, $hasValue, $name, $shortForm, $defaultValue)
    {
        $this->isRequired = $isRequired;
        $this->hasValue = $hasValue;
        $this->name = $name;
        $this->shortForm = $shortForm;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->isRequired;
    }

    /**
     * @return boolean
     */
    public function hasValue()
    {
        return $this->hasValue;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getShortForm()
    {
        return $this->shortForm;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}