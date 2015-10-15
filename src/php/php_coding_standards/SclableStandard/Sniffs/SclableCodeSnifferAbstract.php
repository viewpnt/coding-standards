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

namespace SclableStandard\Sniffs;

/**
 * Class SclableCodeSnifferAbstract
 *
 *
 * @package SclableStandard\Sniffs
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
abstract class SclableCodeSnifferAbstract implements \PHP_CodeSniffer_Sniff
{
    use SclableCodeSnifferTrait;

    /**
     * @return void
     */
    abstract protected function sniff();

    /**
     * @inheritdoc
     */
    public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $this->load($phpcsFile, $stackPtr);
        $this->sniff();
    }
}
