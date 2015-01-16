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

use Psr\Log\LogLevel;

class ConsoleTagForLevelDecorator extends ConsoleMessageDecorator
{
    const ERROR = "fg=red";
    const WARNING = "fg=yellow";
    const DEBUG = "fg=cyan";
    const NOTICE = "fg=green";
    const INFO = false;

    /**
     * @var array
     */
    private $levelTagMap = array(
        LogLevel::EMERGENCY => self::ERROR,
        LogLevel::ALERT => self::ERROR,
        LogLevel::CRITICAL => self::ERROR,
        LogLevel::ERROR => self::ERROR,
        LogLevel::WARNING => self::WARNING,
        LogLevel::NOTICE => self::NOTICE,
        LogLevel::INFO => self::INFO,
        LogLevel::DEBUG => self::DEBUG,
    );

    public function __construct(ConsoleMessageDecorator $decorator, $levelTagMap = array())
    {
        parent::__construct($decorator);
        $this->levelTagMap = $levelTagMap + $this->levelTagMap;
    }

    public function decorate($level, $message)
    {
        $tag = $this->levelTagMap[$level];

        if (! $tag) {
            return $this->decorator->decorate($level, $message);
        }

        return sprintf('<%1$s>%2$s</%1$s>', $tag, $this->decorator->decorate($level, $message));
    }
}