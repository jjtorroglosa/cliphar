<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Container\Exception;

use Interop\Container\Exception\ContainerException as BaseContainerException;

/**
 * Class NotFoundException 
 */
class ContainerException extends \Exception implements BaseContainerException
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}