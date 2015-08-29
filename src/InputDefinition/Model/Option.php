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
     * @param string $name
     * @param string $abbreviatedName
     * @param string $defaultValue
     */
    public function __construct($isRequired, $name, $abbreviatedName, $defaultValue)
    {
        $this->isRequired = $isRequired;
        $this->name = $name;
        $this->abbreviatedName = $abbreviatedName;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->isRequired && !$this->hasDefaultValue();
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
        return $this->defaultValue !== null;
    }
}