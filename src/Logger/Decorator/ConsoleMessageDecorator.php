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

class ConsoleMessageDecorator
{
    /**
     * @var ConsoleMessageDecorator
     */
    protected $decorator;

    public function __construct($decorator = null)
    {
        $this->decorator = $decorator;
    }

    /**
     * @param string $level
     * @param string $message
     * @return mixed
     */
    public function decorate($level, $message)
    {
        return $message;
    }
}