<?php
/**
 * This file is part of the Sclable LaTex Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SclableStandard\Sniffs\ControlStructures;

use SclableStandard\Sniffs\SclableCodeSnifferAbstract;

/**
 * Class SclableStandard_Sniffs_ControlStructures_ControlSignature
 *
 *
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
class ControlSignatureSniff extends SclableCodeSnifferAbstract
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
        'PHP',
        'JS',
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return int[]
     */
    public function register()
    {
        return array(
            T_TRY,
            T_CATCH,
            T_DO,
            T_WHILE,
            T_FOR,
            T_IF,
            T_FOREACH,
            T_ELSE,
            T_ELSEIF,
            T_SWITCH,
        );

    }

    /**
     * @inheritdoc
     */
    public function sniff()
    {
        if (isset($this->tokens[($this->stackPointer + 1)]) === false) {
            return;
        }

        $this->singleSpaceAfterKeyWord();
        $this->singleSpaceAfterClosingParenthesis();
        $this->singleNewLineAfterOpeningBrace();
        $this->checkMultiKeyWordStructures();

    }

    /**
     * Single space after the keyword.
     */
    private function singleSpaceAfterKeyWord()
    {
        $found = 1;
        if ($this->tokens[($this->stackPointer + 1)]['code'] !== T_WHITESPACE) {
            $found = 0;
            $fixer = function (\PHP_CodeSniffer_Fixer $fixer) {
                $fixer->addContent($this->stackPointer, ' ');
            };
        } else {
            if ($this->tokens[($this->stackPointer + 1)]['content'] !== ' ') {
                if (strpos($this->tokens[($this->stackPointer + 1)]['content'], $this->phpCsFile->eolChar) !== false) {
                    $found = 'newline';
                } else {
                    $found = strlen($this->tokens[($this->stackPointer + 1)]['content']);
                }
            }
            $fixer = function (\PHP_CodeSniffer_Fixer $fixer) use ($found) {
                if ($found === 0) {
                    $fixer->addContent($this->stackPointer, ' ');
                } else {
                    $fixer->replaceToken(($this->stackPointer + 1), ' ');
                }
            };
        }

        if ($found === 1) {
            return;
        }

        $msg = 'Expected 1 space after %s keyword; %s found';
        $type = 'SpaceAfterKeyword';
        $data = array(
            strtoupper($this->tokens[$this->stackPointer]['content']),
            $found,
        );

        if (strtoupper($this->tokens[$this->stackPointer]['type']) === 'T_ELSE') {
            $this->addWarning($msg, $this->stackPointer, $type, $data, $fixer);
            return;
        }

        $this->addError($msg, $this->stackPointer, $type, $data, $fixer);
    }

    /**
     * Single space after closing parenthesis.
     */
    private function singleSpaceAfterClosingParenthesis()
    {
        if (isset($this->tokens[$this->stackPointer]['parenthesis_closer']) !== true
            || isset($this->tokens[$this->stackPointer]['scope_opener']) !== true
        ) {
            return;
        }

        $closer = $this->tokens[$this->stackPointer]['parenthesis_closer'];
        $opener = $this->tokens[$this->stackPointer]['scope_opener'];
        $content = $this->phpCsFile->getTokensAsString(($closer + 1), ($opener - $closer - 1));

        if ($content === ' ') {
            return;
        }

        $found = str_replace($this->phpCsFile->eolChar, '\n', $content);
        $found = strlen($found);

        $this->addWarning(
            'Expected 1 space after closing parenthesis; found %s',
            $closer,
            'SpaceAfterCloseParenthesis',
            [$found],
            function (\PHP_CodeSniffer_Fixer $fixer) use ($closer, $opener) {
                if ($closer === ($opener - 1)) {
                    $fixer->addContent($closer, ' ');
                } else {
                    $fixer->beginChangeset();
                    $fixer->addContent($closer, ' ' . $this->tokens[$opener]['content']);
                    $fixer->replaceToken($opener, '');

                    if ($this->tokens[$opener]['line'] !== $this->tokens[$closer]['line']) {
                        $next = $this->phpCsFile->findNext(T_WHITESPACE, ($opener + 1), null, true);
                        if ($this->tokens[$next]['line'] !== $this->tokens[$opener]['line']) {
                            for ($i = ($opener + 1); $i < $next; $i++) {
                                $fixer->replaceToken($i, '');
                            }
                        }
                    }

                    $fixer->endChangeset();
                }
            }
        );

    }

    /**
     * Single newline after opening brace.
     */
    private function singleNewLineAfterOpeningBrace()
    {
        if (isset($this->tokens[$this->stackPointer]['scope_opener']) === false) {
            $this->checkDoWhileNoSpaceAfterClosingParenthesis();
            return;
        }
        $opener = $this->tokens[$this->stackPointer]['scope_opener'];
        for ($next = ($opener + 1); $next < $this->phpCsFile->numTokens; $next++) {
            $code = $this->tokens[$next]['code'];

            if ($code === T_WHITESPACE) {
                continue;
            }

            // Skip all empty tokens on the same line as the opener.
            if ($this->tokens[$next]['line'] === $this->tokens[$opener]['line']
                && (isset(\PHP_CodeSniffer_Tokens::$emptyTokens[$code]) === true
                    || $code === T_CLOSE_TAG)
            ) {
                continue;
            }

            // We found the first bit of a code, or a comment on the
            // following line.
            break;
        }

        $this->reportErrorForSingleNewLineAfterOpeningBraceViolation(
            $this->tokens[$next]['line'] - $this->tokens[$opener]['line'],
            $opener,
            $next
        );

    }

    /**
     * @param int $found
     * @param int $opener
     * @param int $next
     */
    private function reportErrorForSingleNewLineAfterOpeningBraceViolation($found, $opener, $next)
    {
        if ($found === 1) {
            return;
        } elseif ($found === 0) {
            $fixCallback = function (\PHP_CodeSniffer_Fixer $fixer) use ($opener, $next) {
                $fixer->beginChangeset();
                $fixer->addContent($opener, $this->phpCsFile->eolChar);
                $fixer->endChangeset();
            };
        } else {
            $fixCallback = function (\PHP_CodeSniffer_Fixer $fixer) use ($opener, $next) {
                $fixer->beginChangeset();
                for ($i = ($opener + 1); $i < $next; $i++) {
                    if ($this->tokens[$i]['line'] === $this->tokens[$next]['line']) {
                        break;
                    }
                    $fixer->replaceToken($i, '');
                }
                $fixer->addContent($opener, $this->phpCsFile->eolChar);
                $fixer->endChangeset();
            };
        }

        $this->addError(
            'Expected 1 newline after opening brace; %s found',
            $opener,
            'NewlineAfterOpenBrace',
            [$found],
            $fixCallback
        );
    }

    /**
     * check for correct do ... while(); statement.
     */
    private function checkDoWhileNoSpaceAfterClosingParenthesis()
    {
        if ($this->tokens[$this->stackPointer]['code'] !== T_WHILE) {
            return;
        }

        // Zero spaces after parenthesis closer.
        $closer = $this->tokens[$this->stackPointer]['parenthesis_closer'];
        $found = 0;
        if ($this->tokens[($closer + 1)]['code'] === T_WHITESPACE) {
            if (strpos($this->tokens[($closer + 1)]['content'], $this->phpCsFile->eolChar) !== false) {
                $found = 'newline';
            } else {
                $found = strlen($this->tokens[($closer + 1)]['content']);
            }
        }

        if ($found !== 0) {
            $this->addError(
                'Expected 0 spaces before semicolon; %s found',
                $closer,
                'SpaceBeforeSemicolon',
                [$found],
                function (\PHP_CodeSniffer_Fixer $fixer) use ($closer) {
                    $fixer->replaceToken(($closer + 1), '');
                }
            );
        }
    }

    /**
     * check multi key word structures
     */
    private function checkMultiKeyWordStructures()
    {
        $code = $this->tokens[$this->stackPointer]['code'];
        if (!in_array($code, [T_DO, T_ELSE, T_ELSEIF, T_CATCH], true)) {
            return;
        }

        if ($this->tokens[$this->stackPointer]['code'] === T_DO) {
            if (isset($this->tokens[$this->stackPointer]['scope_closer']) === false) {
                return;
            }
            $closer = $this->tokens[$this->stackPointer]['scope_closer'];
        } else {
            $closer = $this->phpCsFile->findPrevious(
                \PHP_CodeSniffer_Tokens::$emptyTokens,
                ($this->stackPointer - 1),
                null,
                true
            );
            if ($closer === false || $this->tokens[$closer]['code'] !== T_CLOSE_CURLY_BRACKET) {
                return;
            }
        }

        // Single space after closing brace.
        $found = $this->countWhitespacesForMultiKeyWordStructure($closer);

        if ($found === 1) {
            return;
        } elseif ($found === 0) {
            $fixCallback = function (\PHP_CodeSniffer_Fixer $fixer) use ($closer) {
                $fixer->addContent($closer, ' ');
            };
        } else {
            $fixCallback = function (\PHP_CodeSniffer_Fixer $fixer) use ($closer) {
                $fixer->replaceToken(($closer + 1), ' ');
            };
        }

        $this->addWarning(
            'Expected 1 space after closing brace; %s found',
            $closer,
            'SpaceAfterCloseBrace',
            [$found],
            $fixCallback
        );
    }

    /**
     *
     * @param int $closeBracketPointer
     * @return int|string
     */
    private function countWhitespacesForMultiKeyWordStructure($closeBracketPointer)
    {
        if ($this->tokens[($closeBracketPointer + 1)]['content'] === ' ') {
            return 1;
        } elseif ($this->tokens[($closeBracketPointer + 1)]['code'] !== T_WHITESPACE) {
            return 0;
        } elseif (strpos($this->tokens[($closeBracketPointer + 1)]['content'], $this->phpCsFile->eolChar) !== false) {
            return 'newline';
        }
        return strlen($this->tokens[($closeBracketPointer + 1)]['content']);
    }
}
