<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Cliphar\BaseApplication;

if (! function_exists('cmd')) {
    function cmd($name, $definition, $callable) {
        BaseApplication::getInstance()->addCommand($name, $definition, $callable);
    }
}