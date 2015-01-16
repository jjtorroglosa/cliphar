<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Logger\Decorator;

class ConsoleLevelDecorator extends ConsoleMessageDecorator
{
    public function __construct(ConsoleMessageDecorator $decorator)
    {
        parent::__construct($decorator);
    }


    public function decorate($level, $message)
    {
        return sprintf("[%s] %s", strtoupper($level), $this->decorator->decorate($level, $message));
    }

}