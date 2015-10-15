<?php
/**
 * This file is part of the Sclable Coding Standards Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SclableStandard\Sniffs\Methods;

use SclableStandard\Sniffs\SclableCodeSnifferTrait;

/**
 * Class CamelCapsMethodNameSniff
 *
 *
 * @package SclableStandard\Sniffs\Methods
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
class CamelCapsMethodNameSniff extends \PSR1_Sniffs_Methods_CamelCapsMethodNameSniff
{
    use SclableCodeSnifferTrait;

    /**
     * Processes the tokens within the scope.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being processed.
     * @param int $stackPtr The position where this token was
     *                                        found.
     * @param int $currScope The position of the current scope.
     *
     * @return void
     */
    protected function processTokenWithinScope(\PHP_CodeSniffer_File $phpcsFile, $stackPtr, $currScope)
    {
        $this->load($phpcsFile, $stackPtr);

        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        if ($methodName === null) {
            // Ignore closures.
            return;
        }

        // Ignore magic methods.
        if (preg_match('|^__|', $methodName) !== 0) {
            $magicPart = strtolower(substr($methodName, 2));
            if (isset($this->magicMethods[$magicPart]) === true
                || isset($this->methodsDoubleUnderscore[$magicPart]) === true
            ) {
                return;
            }
        }

        if (preg_match('/^(get|set)_/', $methodName) !== 0) {
            $testName = lcfirst(substr($methodName, 4));
        } else {
            $testName = ltrim($methodName, '_');
        }

        if (\PHP_CodeSniffer::isCamelCaps($testName, false, true, false) === false) {
            $error = 'Method name "%s" is not in camel caps format';
            $className = $phpcsFile->getDeclarationName($currScope);
            $errorData = array($className . '::' . $methodName);
            $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $errorData);
            $phpcsFile->recordMetric($stackPtr, 'CamelCase method name', 'no');
        } else {
            $phpcsFile->recordMetric($stackPtr, 'CamelCase method name', 'yes');
        }
    }
}
