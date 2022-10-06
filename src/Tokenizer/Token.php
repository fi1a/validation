<?php

declare(strict_types=1);

namespace Fi1a\Validation\Tokenizer;

use Fi1a\Tokenizer\AToken;

/**
 * Токен
 */
class Token extends AToken
{
    /**
     * Неизвестный токен
     */
    public const T_UNKNOWN_TOKEN_TYPE = 1;

    /**
     * ' '
     */
    public const T_WHITESPACE = 10;

    /**
     * '|'
     */
    public const T_SEPARATOR = 20;

    /**
     * 'required'
     */
    public const T_RULE = 30;

    /**
     * '('
     */
    public const T_OPEN_PARENTHESES = 40;

    /**
     * ')'
     */
    public const T_CLOSE_PARENTHESES = 50;

    /**
     * '"''
     */
    public const T_QUOTE = 60;

    /**
     * ','
     */
    public const T_COMMA_SEPARATOR = 70;

    /**
     * '10m'
     */
    public const T_ARGUMENT = 80;

    /**
     * 'true'
     */
    public const T_TRUE = 90;

    /**
     * 'false'
     */
    public const T_FALSE = 100;

    /**
     * 'null'
     */
    public const T_NULL = 110;

    /**
     * @var array
     */
    protected static $types = [
        self::T_UNKNOWN_TOKEN_TYPE,
        self::T_WHITESPACE,
        self::T_SEPARATOR,
        self::T_OPEN_PARENTHESES,
        self::T_CLOSE_PARENTHESES,
        self::T_QUOTE,
        self::T_COMMA_SEPARATOR,
        self::T_TRUE,
        self::T_FALSE,
        self::T_NULL,
        self::T_ARGUMENT,
        self::T_RULE,
    ];

    /**
     * Возвращает доступные типы токенов
     *
     * @return int[]
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    protected function getTypes(): array
    {
        return static::$types;
    }
}
