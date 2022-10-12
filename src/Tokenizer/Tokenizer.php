<?php

declare(strict_types=1);

namespace Fi1a\Validation\Tokenizer;

use Fi1a\Tokenizer\AParseFunction;
use Fi1a\Tokenizer\IToken;

/**
 * Лексический анализатор
 */
class Tokenizer extends AParseFunction
{
    /**
     * @var string
     */
    private $whiteSpaceReturn = 'parse';

    /**
     * @var int[]
     */
    protected static $values = [
        'false' => Token::T_FALSE,
        'true' => Token::T_TRUE,
        'null' => Token::T_NULL,
    ];

    /**
     * @inheritDoc
     */
    public function __construct(string $source, ?string $encoding = null)
    {
        $this->setParseFunction('parse');
        parent::__construct($source, $encoding);
    }

    /**
     * Базовая функция парсинга
     *
     * @param IToken[]    $tokens
     */
    protected function parse(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        do {
            if ($current < 0) {
                $current = 0;
            }
            if (!$source || $current >= mb_strlen($source)) {
                $finish = true;
                if ($image) {
                    $type = Token::T_RULE;
                }

                return;
            }

            $symbol = mb_substr($source, $current, 1);
            $prevSymbol = mb_substr($source, $current - 1, 1);

            if ($symbol === '|' && $prevSymbol !== '\\') {
                if ($image) {
                    $type = Token::T_RULE;
                }
                $this->setParseFunction('parseSeparator');

                return;
            }
            if ($symbol === '(' && $prevSymbol !== '\\') {
                if ($image) {
                    $type = Token::T_RULE;
                }
                $this->setParseFunction('parseOpenParentheses');

                return;
            }
            if (preg_match('/[\s\t\n]/mui', $symbol)) {
                $this->whiteSpaceReturn = 'parse';
                $this->setParseFunction('parseWhitespace');
                if ($image) {
                    $type = Token::T_RULE;
                }

                return;
            }

            $image .= $symbol;
            $current++;
        } while (true);
    }

    /**
     * Парсинг пробела
     *   -         - -
     * {{ key1:key2 | specifier("1", "2")}}
     *   -         - -
     *
     * @param IToken[]    $tokens
     */
    protected function parseWhitespace(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $type = Token::T_WHITESPACE;
        do {
            $symbol = mb_substr($source, $current, 1);
            $loop = preg_match('/[\s\t\n]/mui', $symbol) && $current < mb_strlen($source);
            if ($loop) {
                $image .= $symbol;
            }
            $current++;
        } while ($loop);
        $current--;
        $this->setParseFunction($this->whiteSpaceReturn);
    }

    /**
     * Парсинг открытия скобки указания модификаторов
     *
     *         -
     * required()
     *         -
     *
     * @param IToken[]    $tokens
     */
    protected function parseOpenParentheses(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $symbol = mb_substr($source, $current, 1);

        $type = Token::T_OPEN_PARENTHESES;
        $image = $symbol;
        $current++;
        $this->setParseFunction('parseArgument');
    }

    /**
     * Парсинг закрытия скобки указания модификаторов
     *          -
     * required()
     *          -
     *
     * @param IToken[]    $tokens
     */
    protected function parseCloseParentheses(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $symbol = mb_substr($source, $current, 1);
        if ($symbol === ')') {
            $type = Token::T_CLOSE_PARENTHESES;
            $image = $symbol;
        }
        $current++;
        $this->setParseFunction('parse');
    }

    /**
     * Парсинг открытия скобки указания модификаторов
     *
     *         -
     * required()
     *         -
     *
     * @param IToken[]    $tokens
     */
    protected function parseArgument(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $matchRegexp = '/[\s\t\n\,\(\)]/mui';

        do {
            $symbol = mb_substr($source, $current, 1);
            $prevSymbol = mb_substr($source, $current - 1, 1);

            if (preg_match('/[\s\t\n]/mui', $symbol) && !$quote && !$single) {
                $this->whiteSpaceReturn = 'parseArgument';
                $this->setParseFunction('parseWhitespace');
                if ($image !== '') {
                    $type = Token::T_ARGUMENT;
                }

                return;
            }
            if ($symbol === ',' && !$quote && !$single) {
                $this->setParseFunction('parseCommaSeparator');
                if ($image  !== '') {
                    $type = Token::T_ARGUMENT;
                }

                return;
            }
            // @codingStandardsIgnoreStart
            if ((($symbol === '"' && !$single) || ($symbol === '\'' && !$quote)) && $prevSymbol !== '\\') {
                $this->setParseFunction('parseQuote');
                if ($image !== '') {
                    $type = Token::T_ARGUMENT;
                }

                return;
            }
            // @codingStandardsIgnoreEnd

            foreach (array_keys(static::$values) as $value) {
                $key = mb_strtolower(mb_substr($source, $current, mb_strlen((string) $value)));
                $nextKey = mb_substr($source, $current + mb_strlen((string) $value), 1);
                if (
                    $key === $value && !$quote && !$single
                    && (
                        !$nextKey
                        || (
                            preg_match($matchRegexp, $prevSymbol)
                            && preg_match($matchRegexp, $nextKey)
                        )
                    )
                ) {
                    $this->setParseFunction('parseValue');

                    return;
                }
            }

            $loop = ($symbol !== ')' || $quote || $single) && $current < mb_strlen($source);
            if ($loop) {
                $image .= $symbol;
            }

            $current++;
        } while ($loop);
        $current--;
        $this->setParseFunction('parseCloseParentheses');
        if ($image !== '') {
            $type = Token::T_ARGUMENT;
        }
    }

    /**
     * Парсинг значения
     *
     *          ----
     * required(true)
     *          ----
     *
     * @param IToken[] $tokens
     */
    protected function parseValue(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        foreach (static::$values as $value => $setType) {
            $key = mb_substr($source, $current, mb_strlen((string) $value));
            if (mb_strtolower($key) === $value) {
                $type = $setType;
                $image = $key;
                $current += mb_strlen($value);

                break;
            }
        }

        $this->setParseFunction('parseArgument');
    }

    /**
     * Парсинг открытия скобки указания модификаторов
     *
     *          -   -
     * required("100")
     *          -   -
     *
     * @param IToken[]    $tokens
     */
    protected function parseQuote(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $symbol = mb_substr($source, $current, 1);
        $type = Token::T_QUOTE;
        $image = $symbol;
        if ($image === '"' && !$single) {
            $quote = !$quote;
        }
        if ($image === '\'' && !$quote) {
            $single = !$single;
        }
        $current++;
        $this->setParseFunction('parseArgument');
    }

    /**
     * Парсинг запятой
     *              -
     * required(true, false)
     *              -
     *
     * @param IToken[]    $tokens
     */
    protected function parseCommaSeparator(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $symbol = mb_substr($source, $current, 1);
        $type = Token::T_COMMA_SEPARATOR;
        $image = $symbol;
        $current++;
        $this->setParseFunction('parseArgument');
    }

    /**
     * Парсинг открытия оператора
     *
     *         -
     * required|null
     *         -
     *
     * @param IToken[]    $tokens
     */
    protected function parseSeparator(
        bool &$finish,
        string &$source,
        int &$current,
        string &$image,
        ?int &$type,
        array &$tokens,
        bool &$quote,
        bool &$single
    ): void {
        $symbol = mb_substr($source, $current, 1);
        $image = $symbol;
        $type = Token::T_SEPARATOR;
        $this->setParseFunction('parse');
        $current++;
    }

    /**
     * @inheritDoc
     */
    public static function getTokenFactory()
    {
        return TokenFactory::class;
    }
}
