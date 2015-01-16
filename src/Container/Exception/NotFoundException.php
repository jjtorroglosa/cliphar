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

use Interop\Container\Exception\NotFoundException as BaseNotFoundException;

/**
 * Class NotFoundException 
 */
class NotFoundException extends ContainerException implements BaseNotFoundException
{
}