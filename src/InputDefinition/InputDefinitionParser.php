<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\InputDefinition;

use Cliphar\InputDefinition\Exception\ParserException;
use Cliphar\InputDefinition\Lexer\DefinitionLexer;
use Cliphar\InputDefinition\Model\Argument;
use Cliphar\InputDefinition\Model\InputDefinition;
use Cliphar\InputDefinition\Model\Option;

class InputDefinitionParser
{
    /**
     * @var \Cliphar\InputDefinition\Lexer\DefinitionLexer
     */
    private $lexer;

    /**
     * @var InputDefinition
     */
    private $inputDefinition;

    /**
     * @param string $string
     * @return InputDefinition
     * @throws \Cliphar\InputDefinition\Exception\ParserException
     */
    public function parse($string)
    {
        $this->lexer = new DefinitionLexer($string);
        list($token, $value, $position) = $this->lexer->getNextToken();
        $this->inputDefinition = new InputDefinition();

        switch ($token) {
            case DefinitionLexer::T_OPEN_ARGUMENT_SYMBOL:
                $this->consumeArgumentsList();
                break;
            case DefinitionLexer::T_OPEN_OPTION_SYMBOL:
                $this->consumeOptionsList();
                break;
            case null:
                break;
            default:
                throw new ParserException("Expected argument or option", $token, $value, $position);
        }

        return $this->inputDefinition;
    }

    private function consumeOptionsList()
    {
        list($token, $value, $pos) = $this->lexer->getLastOccurrence();
        while ($token !== null) {
            switch ($token) {
                case DefinitionLexer::T_OPEN_OPTION_SYMBOL:
                    $this->inputDefinition->addOption($this->consumeOption());
                    list($token) = $this->lexer->getNextToken();
                    break;
                case DefinitionLexer::T_OPEN_ARGUMENT_SYMBOL:
                    $this->consumeArgumentsList();
                    return;
                default:
                    throw new ParserException("Expected option or argument", $token, $value, $pos);
            }
        }
    }

    private function consumeOption()
    {
        $abbreviated = "";

        list($token, $string, $pos) = $this->lexer->getNextToken();
        if ($token !== DefinitionLexer::T_NAME) {
            throw new ParserException("Expected T_NAME", $token, $string, $pos);
        }

        $name = $string;
        list($token, $string, $pos) = $this->lexer->getNextToken();

        if ($token === DefinitionLexer::T_ABBREV_SEPARATOR) {
            list($token, $string, $pos) = $this->lexer->getNextToken();
            if ($token !== DefinitionLexer::T_ABBREV) {
                throw new ParserException("Expected abbreviated name", $token, $string, $pos);
            }
            $abbreviated = $string;

            list($token, $string, $pos) = $this->lexer->getNextToken();
            if ($token !== DefinitionLexer::T_CLOSE_OPTION_SYMBOL) {
                throw new ParserException("Expected T_CLOSE_OPTION_SYMBOL", $token, $string, $pos);
            }
        } else if ($token !== DefinitionLexer::T_CLOSE_OPTION_SYMBOL) {
            throw new ParserException("Expected abbreviated or close option", $token, $string, $pos);
        }

        list($token, $string, $pos) = $this->lexer->getNextToken();
        list($isOptional, $defaultValue) = $this->consumeModifiers($token, $string, $pos);

        return new Option(!$isOptional, $defaultValue !== "", $name, $abbreviated, $defaultValue);
    }

    private function consumeArgumentsList()
    {
        list($token) = $this->lexer->getLastOccurrence();
        while ($token !== null) {
            $this->inputDefinition->addArgument($this->consumeArgument());
            list($token) = $this->lexer->getNextToken();
        }
    }

    private function consumeArgument()
    {
        list($token, $string, $pos) = $this->lexer->getNextToken();
        switch($token) {
            case DefinitionLexer::T_NAME:
                $name = $string;
                break;
            default:
                throw new ParserException("Expected T_NAME", $token, $string, $pos);
        }

        list($token, $string, $pos) = $this->lexer->getNextToken();

        if ($token !== DefinitionLexer::T_CLOSE_ARGUMENT_SYMBOL) {
            throw new ParserException("Expected '>'",$token, $string, $pos);
        }

        list($token, $string, $pos) = $this->lexer->getNextToken();

        list($isOptional, $defaultValue) = $this->consumeModifiers($token, $string, $pos);

        return new Argument($name, !$isOptional, $defaultValue);
    }

    /**
     * @param $token
     * @param $string
     * @param $pos
     * @return array
     * @throws \Cliphar\InputDefinition\Exception\LexerException
     * @throws \Cliphar\InputDefinition\Exception\ParserException
     */
    private function consumeModifiers($token, $string, $pos)
    {
        $isOptional = false;
        $defaultValue = null;
        switch ($token) {
            case DefinitionLexer::T_OPTIONAL_MARK:
                $isOptional = true;
                break;
            case DefinitionLexer::T_EQUAL_SIGN:
                list($token, $string, $pos) = $this->lexer->getNextToken();
                if ($token !== DefinitionLexer::T_STRING_WITH_SPACES) {
                    throw new ParserException("Expected default value", $token, $string, $pos);
                }
                $defaultValue = str_replace('"', '', $string);
                break;
            case DefinitionLexer::T_WHITESPACES:
                break;
            case null:
                break;
            default:
                throw new ParserException("Unexpected token found", $token, $string, $pos);
        }

        if ($token !== DefinitionLexer::T_WHITESPACES) {
            list($token, $string, $pos) = $this->lexer->getNextToken();
            switch ($token) {
                case DefinitionLexer::T_WHITESPACES:
                    break;
                case null:
                    break;
                default:
                    throw new ParserException("Expected whitespace", $token, $string, $pos);
            }
            return array($isOptional, $defaultValue);
        }
        return array($isOptional, $defaultValue);
    }
}