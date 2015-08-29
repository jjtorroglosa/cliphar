<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Application\Fixtures;


use Cliphar\BaseApplication;

class TestApplication extends BaseApplication
{
    /**
     * @return string[]
     */
    protected function getCommands()
    {
        return array();
    }

    /**
     * @return string[]
     */
    protected function getProviders()
    {
        return array();
    }

    /**
     * @return string
     */
    protected function getVersion()
    {
        return "anyversion";
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return "anyname";
    }
}