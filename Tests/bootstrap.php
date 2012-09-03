<?php

/*
 * This file is part of the MZ\PostMarkBundle
 *
 * (c) Miguel Perez <miguel@miguelpz.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/');
}

require $autoloadFile;
