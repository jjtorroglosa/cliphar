<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\InputDefinition\Exception;

use Exception;

class OptionsParsingException extends Exception
{
    public function __construct($reason, $tokenFound, $valueFound, $position)
    {
        parent::__construct("{$reason}. Token {$tokenFound} with value '{$valueFound}' found at pos {$position}");
    }
}