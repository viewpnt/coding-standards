<?php
/**
 * This file is part of the Sclable Coding Standards Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
