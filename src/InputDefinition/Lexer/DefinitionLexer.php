<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\InputDefinition\Lexer;


use Cliphar\InputDefinition\Exception\LexerException;

class DefinitionLexer
{
    const T_OPEN_OPTION_SYMBOL    = "T_OPEN_OPTION_SYMBOL";
    const T_CLOSE_OPTION_SYMBOL   = "T_CLOSE_OPTION_SYMBOL";
    const T_OPEN_ARGUMENT_SYMBOL  = "T_OPEN_ARGUMENT_SYMBOL";
    const T_CLOSE_ARGUMENT_SYMBOL = "T_CLOSE_ARGUMENT_SYMBOL";
    const T_NAME                  = "T_NAME";
    const T_ABBREV                = "T_ABBREV";
    const T_ABBREV_SEPARATOR      = "T_ABBREV_SEPARATOR";
    const T_STRING_WITH_SPACES    = "T_STRING_WITH_SPACES";
    const T_EQUAL_SIGN            = "T_EQUAL_SIGN";
    const T_OPTIONAL_MARK         = "T_OPTIONAL_MARK";
    const T_WHITESPACES           = "T_WHITESPACES";

    private static $tokenToRegex = null;

    private static function getTokenToRegex()
    {
        if (self::$tokenToRegex === null) {
            self::$tokenToRegex = array(
                self::T_OPEN_OPTION_SYMBOL    => '^\[',
                self::T_CLOSE_OPTION_SYMBOL   => '^\]',
                self::T_OPEN_ARGUMENT_SYMBOL  => '^\<',
                self::T_CLOSE_ARGUMENT_SYMBOL => '^\>',
                self::T_OPTIONAL_MARK         => '^\?',
                self::T_EQUAL_SIGN            => '^=',
                self::T_STRING_WITH_SPACES    => '^"[^"]*"',
                self::T_NAME                  => '^[A-Za-z0-9\-\_]{2,}',
                self::T_ABBREV                => '^[A-Za-z]',
                self::T_ABBREV_SEPARATOR      => '^\|',
                self::T_WHITESPACES           => '^\s+'
            );
        }

        return self::$tokenToRegex;
    }

    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $strlen;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var string
     */
    private $lastOccurrence;

    /**
     * DefinitionLexer constructor.
     * @param string $string
     */
    public function __construct($string)
    {
        $this->string = $string;
        $this->strlen = strlen($string);
    }

    public function getNextToken()
    {
        foreach (self::getTokenToRegex() as $token => $regex) {
            if ($this->offset >= $this->strlen) {
                $this->lastOccurrence = array(null, null, null);
                return $this->lastOccurrence;
            }
            $substr = substr($this->string, $this->offset);
            $result = preg_match('~'. $regex . '~', $substr, $matches, 0);
            if ($result) {
                $this->offset += strlen($matches[0]);
                $this->lastOccurrence = array($token, $matches[0], $this->offset);
                return $this->lastOccurrence;
            }
        }

        throw new LexerException("Unexpected character found at: {$substr}". PHP_EOL);
    }

    public function getLastOccurrence()
    {
        return $this->lastOccurrence;
    }
}