<?php
/**
 * ----------------------------------------------------------------------------
 * This code is part of the Sclable Business Application Development Platform
 * and is subject to the provisions of your License Agreement with
 * Sclable Business Solutions GmbH.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 * ----------------------------------------------------------------------------
 */

namespace SclableStandard\Sniffs\Commenting;

use SclableStandard\Sniffs\SclableCodeSnifferAbstract;

/**
 * Class SclableStandard_Sniffs_Commenting_DisallowHashCommentsSniff
 *
 *
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
class DisallowHashCommentsSniff extends SclableCodeSnifferAbstract
{

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * An example return value for a sniff that wants to listen for whitespace
     * and any comments would be:
     *
     * <code>
     *    return array(
     *            T_WHITESPACE,
     *            T_DOC_COMMENT,
     *            T_COMMENT,
     *           );
     * </code>
     *
     * @return int[]
     * @see    Tokens.php
     */
    public function register()
    {
        return [T_COMMENT];
    }

    /**
     * @inheritdoc
     */
    public function sniff()
    {
        $token = $this->tokens[$this->stackPointer]['content'];
        if ($token{0} === '#') {
            $this->addError(
                'Hash comments are prohibited; found %s',
                $this->stackPointer,
                'Found',
                [trim($token)],
                function (\PHP_CodeSniffer_Fixer $fixer) use ($token) {
                    $fixer->replaceToken($this->stackPointer, '//' . substr($token, 1));
                }
            );
        }
    }
}
