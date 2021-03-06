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


use Cliphar\InputDefinition\Lexer\DefinitionLexer;
use PHPUnit_Framework_TestCase;

class DefinitionLexerUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Cliphar\InputDefinition\Lexer\DefinitionLexer
     */
    private $definitionLexer;

    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\LexerException
     */
    public function testUnexpectedTokenThrowsException()
    {
        $this->definitionLexer = new DefinitionLexer("*");

        $this->definitionLexer->getNextToken();
    }

    public function testOptionTokens()
    {
        $this->definitionLexer = new DefinitionLexer("[name|a]?");

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_OPEN_OPTION_SYMBOL, $token);
        $this->assertEquals("[", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_NAME, $token);
        $this->assertEquals("name", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_ABBREV_SEPARATOR, $token);
        $this->assertEquals("|", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_ABBREV, $token);
        $this->assertEquals("a", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_CLOSE_OPTION_SYMBOL, $token);
        $this->assertEquals("]", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_OPTIONAL_MARK, $token);
        $this->assertEquals("?", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertNull($token);
        $this->assertNull($string);
    }

    public function tesArgumentTokens()
    {
        $this->definitionLexer = new DefinitionLexer("<name|a>=\"quoted string\"");

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_OPEN_ARGUMENT_SYMBOL, $token);
        $this->assertEquals("<", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_NAME, $token);
        $this->assertEquals("name", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_ABBREV_SEPARATOR, $token);
        $this->assertEquals("|", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_ABBREV, $token);
        $this->assertEquals("a", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_CLOSE_ARGUMENT_SYMBOL, $token);
        $this->assertEquals(">", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_EQUAL_SIGN, $token);
        $this->assertEquals("=", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertEquals(DefinitionLexer::T_STRING_WITH_SPACES, $token);
        $this->assertEquals("\"quoted string\"", $string);

        list($token, $string) = $this->definitionLexer->getNextToken();
        $this->assertNull($token);
        $this->assertNull($string);
    }

    /**
     * @expectedException \Cliphar\InputDefinition\Exception\LexerException
     */
    public function testException()
    {
        $this->definitionLexer = new DefinitionLexer("<name>=\"quoted string");

        $this->definitionLexer->getNextToken();
        $this->definitionLexer->getNextToken();
        $this->definitionLexer->getNextToken();
        $this->definitionLexer->getNextToken();
        $this->definitionLexer->getNextToken();
    }
}