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

trait SclableCodeSnifferTrait
{
    /** @var \PHP_CodeSniffer_File The PHP_CodeSniffer file where the token was found. */
    protected $phpCsFile;

    /** @var int the position in the PHP_CodeSniffer file's token stack where the token was found. */
    protected $stackPointer;

    /** @var array a list of tokens */
    protected $tokens;

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     */
    protected function load(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $this->phpCsFile = $phpCsFile;
        $this->stackPointer = $stackPointer;
        $this->tokens = $phpCsFile->getTokens();
    }

    /**
     *
     * @see \PHP_CodeSniffer_File::addError
     *
     * @param string $error
     * @param int $stackPointer
     * @param string $code
     * @param array $data
     * @param callable|null $fixer a callback to fix the error, set to null, if not fixable
     * @param int $severity
     */
    protected function addError($error, $stackPointer, $code = '', $data = [], callable $fixer = null, $severity = 0)
    {
        $recorded = $this->phpCsFile->addError($error, $stackPointer, $code, $data, $severity, is_callable($fixer));

        if ($recorded === true && $this->phpCsFile->fixer->enabled === true && is_callable($fixer)) {
            $fixer($this->phpCsFile->fixer);
        }
    }

    /**
     * add a warning
     *
     * @see \PHP_CodeSniffer_File::addWarning
     * @param string $warning
     * @param int $stackPointer
     * @param string $code
     * @param array $data
     * @param callable|null $fixer a callback to fix the warning, set to null if not fixable
     * @param int $severity
     */
    protected function addWarning(
        $warning,
        $stackPointer,
        $code = '',
        $data = [],
        callable $fixer = null,
        $severity = 0
    ) {
        $recorded = $this->phpCsFile->addWarning($warning, $stackPointer, $code, $data, $severity, is_callable($fixer));

        if ($recorded === true && $this->phpCsFile->fixer->enabled === true && is_callable($fixer)) {
            $fixer($this->phpCsFile->fixer);
        }
    }
}
