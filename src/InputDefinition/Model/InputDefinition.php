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

use Cliphar\InputDefinition\Model\Argument;
use Cliphar\InputDefinition\Model\Option;

class InputDefinition
{
    /**
     * @var Option[]
     */
    private $options;

    /**
     * @var Argument[]
     */
    private $arguments;

    public function addOption(Option $option)
    {
        $this->options[] = $option;
    }

    public function addArgument(Argument $argument)
    {
        $this->arguments[] = $argument;
    }

    /**
     * @return Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return Argument[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}