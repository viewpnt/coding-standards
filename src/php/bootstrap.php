<?php
/**
 * ----------------------------------------------------------------------------
 * This code is part of the Sclable Business Application Development Platform
 * and is subject to the provisions of your License Agreement with
 * Sclable Business Solutions GmbH.
 *
 * @copyright (c) 2016 Sclable Business Solutions GmbH
 * ----------------------------------------------------------------------------
 */

if (file_exists(dirname(__DIR__) . '/../vendor/autoload.php')) {
    include dirname(__DIR__) . '/../vendor/autoload.php';
    $vendorDir = dirname(__DIR__) . '/../vendor';
} elseif (file_exists(dirname(__DIR__) . '/../../../autoload.php')) {
    include dirname(__DIR__) . '/../../../autoload.php';
    $vendorDir = dirname(__DIR__) . '/../../../vendor';
} else {
    echo 'Unable to detect Composer autoload.php file.' . PHP_EOL;
    die(1);
}

return $vendorDir;
