<?php
/**
 * Created by IntelliJ IDEA.
 * User: juanjo
 * Date: 10/8/15
 * Time: 21:18
 */

namespace Cliphar\Options;


class DefinitionLexer
{
    const OPEN_CARET               = '\[';
    const CLOSE_CARET              = '\]';
    const NAME                     = '[A-Za-z0-9\-\_]+';
    const ABBREV                   = '[A-Za-z]';
    const NAME_WITH_ABBREV         = '(' . self::NAME . '\|' . self::ABBREV . ')';
    const NAME_OR_NAME_WITH_ABBREV = '(' . self::NAME . '|' . self::NAME_WITH_ABBREV . ')';
    const STRING_WITH_SPACES       = '"[^"]"';
    const EQUAL                    = '=';
    const OPTIONAL                 = '\?';

    const DEFAULT_VALUE    = self::EQUAL . self::STRING_WITH_SPACES;
    const OPTION_MODIFIERS = '('.self::OPTIONAL.'|'.self::DEFAULT_VALUE.')?';
    const OPTION           = self::OPEN_CARET.self::NAME_OR_NAME_WITH_ABBREV.self::CLOSE_CARET.self::OPTION_MODIFIERS;

    const SEVERAL = '(' . self::OPTION  . '\s+)*('.self::OPTION.')';
}