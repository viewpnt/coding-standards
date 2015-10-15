<?php
/**
 * This file is part of the Sclable LaTex Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SclableStandard\Sniffs\NamingConventions;

use PHP_CodeSniffer_File;

/**
 * Class ValidVariableNameSniff
 *
 *
 * @package SclableStandard\Sniffs\NamingConventions
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
class ValidVariableNameSniff extends \Squiz_Sniffs_NamingConventions_ValidVariableNameSniff
{
    protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $varName = ltrim($tokens[$stackPtr]['content'], '$');
        $memberProps = $phpcsFile->getMemberProperties($stackPtr);
        if (empty($memberProps) === true) {
            // Couldn't get any info about this variable, which
            // generally means it is invalid or possibly has a parse
            // error. Any errors will be reported by the core, so
            // we can ignore it.
            return;
        }

        $errorData = array($varName);

        if (substr($varName, 0, 1) === '_') {
            $error = '%s member variable "%s" must not contain a leading underscore';
            $data = array(
                ucfirst($memberProps['scope']),
                $errorData[0],
            );
            $phpcsFile->addError($error, $stackPtr, 'PublicHasUnderscore', $data);
            return;
        }

        if (\PHP_CodeSniffer::isCamelCaps($varName, false, true, false) === false) {
            $error = 'Member variable "%s" is not in valid camel caps format';
            $phpcsFile->addError($error, $stackPtr, 'MemberNotCamelCaps', $errorData);
        }
    }
}
