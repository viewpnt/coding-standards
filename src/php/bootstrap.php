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

$devEnv = realpath(__DIR__ . '/../../vendor');
$prodEnv = realpath(__DIR__ . '/../../../..');

if (file_exists($devEnv . '/autoload.php')) {
    include $devEnv . '/autoload.php';
    return $devEnv;
} elseif (file_exists($prodEnv . '/autoload.php')) {
    include $prodEnv . '/autoload.php';
    return $prodEnv;
} else {
    echo 'Unable to detect Composer autoload.php file.' . PHP_EOL;
    die(1);
}
