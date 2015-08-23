<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\InputDefinition\Model;

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
    private $abbreviatedName;
    /**
     * @var string
     */
    private $defaultValue;


    /**
     * Option constructor.
     * @param boolean $isRequired
     * @param boolean $hasValue
     * @param string $name
     * @param string $abbreviatedName
     * @param string $defaultValue
     */
    public function __construct($isRequired, $hasValue, $name, $abbreviatedName, $defaultValue)
    {
        $this->isRequired = $isRequired;
        $this->hasValue = $hasValue;
        $this->name = $name;
        $this->abbreviatedName = $abbreviatedName;
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
    public function getAbbreviatedName()
    {
        return $this->abbreviatedName;
    }

    /**
     * @return bool
     */
    public function hasAbbreviatedName()
    {
        return $this->abbreviatedName !== null
            && $this->abbreviatedName !== "";
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue()
    {
        return $this->defaultValue !== null
            && $this->defaultValue !== "";
    }
}