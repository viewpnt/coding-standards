<?php
/**
 * This file is part of the Sclable LaTex Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


////// BOOTSTRAP FILE //////
namespace sclable\latex;

define('PKG_SCLABE_CODING_STANDARDS_VERSION', '@@version@@');

// register package to the class loader
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUndefinedNamespaceInspection */
/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
$classLoader = new \sclable\framework\base\Autoloader('sclable\coding-standards', __DIR__.'/src/php');
/** @noinspection PhpUndefinedMethodInspection */
$classLoader->register();
