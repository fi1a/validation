<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Tokenizer;

use Fi1a\Tokenizer\ITokenizer;
use Fi1a\Validation\Tokenizer\Token;
use Fi1a\Validation\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование лексического анализатора
 */
class TokenizerTest extends TestCase
{
    /**
     * Данные для тестирования лексического анализатора
     *
     * @return mixed[]
     */
    public function dataTokenizer(): array
    {
        return [
            // 0
            [
                'required',
                1,
                ['required'],
                [Token::T_RULE,],
            ],
            // 1
            [
                'required|isNull',
                3,
                ['required', '|', 'isNull'],
                [Token::T_RULE, Token::T_SEPARATOR, Token::T_RULE],
            ],
            // 2
            [
                'required()',
                3,
                ['required', '(', ')'],
                [Token::T_RULE, Token::T_OPEN_PARENTHESES, Token::T_CLOSE_PARENTHESES,],
            ],
            // 3
            [
                'required()|isNull()',
                7,
                ['required', '(', ')', '|', 'isNull', '(', ')'],
                [
                    Token::T_RULE, Token::T_OPEN_PARENTHESES, Token::T_CLOSE_PARENTHESES, Token::T_SEPARATOR,
                    Token::T_RULE, Token::T_OPEN_PARENTHESES, Token::T_CLOSE_PARENTHESES,
                ],
            ],
            // 4
            [
                ' required ( ) | isNull ( ) ',
                15,
                [
                    ' ', 'required', ' ', '(', ' ', ')', ' ', '|', ' ', 'isNull', ' ', '(', ' ', ')', ' ',
                ],
                [
                    Token::T_WHITESPACE, Token::T_RULE, Token::T_WHITESPACE, Token::T_OPEN_PARENTHESES,
                    Token::T_WHITESPACE, Token::T_CLOSE_PARENTHESES, Token::T_WHITESPACE, Token::T_SEPARATOR,
                    Token::T_WHITESPACE, Token::T_RULE, Token::T_WHITESPACE, Token::T_OPEN_PARENTHESES,
                    Token::T_WHITESPACE, Token::T_CLOSE_PARENTHESES, Token::T_WHITESPACE,
                ],
            ],
            // 5
            [
                'required(true, 100)|isNull(false, null, "123")',
                20,
                [
                    'required', '(', 'true', ',', ' ', '100', ')', '|', 'isNull', '(', 'false', ',', ' ',
                    'null', ',', ' ', '"', '123', '"', ')',
                ],
                [
                    Token::T_RULE, Token::T_OPEN_PARENTHESES, Token::T_TRUE, Token::T_COMMA_SEPARATOR,
                    Token::T_WHITESPACE, Token::T_ARGUMENT, Token::T_CLOSE_PARENTHESES, Token::T_SEPARATOR,
                    Token::T_RULE, Token::T_OPEN_PARENTHESES, Token::T_FALSE, Token::T_COMMA_SEPARATOR,
                    Token::T_WHITESPACE, Token::T_NULL, Token::T_COMMA_SEPARATOR, Token::T_WHITESPACE,
                    Token::T_QUOTE, Token::T_ARGUMENT, Token::T_QUOTE, Token::T_CLOSE_PARENTHESES,
                ],
            ],
            // 6
            [
                ' required ( true , 100 ) | isNull ( false , null , " 123 " ) ',
                33,
                [
                    ' ', 'required', ' ', '(', ' ', 'true', ' ', ',', ' ', '100', ' ', ')', ' ', '|', ' ', 'isNull',
                    ' ', '(', ' ', 'false', ' ', ',', ' ', 'null', ' ', ',', ' ', '"', ' 123 ', '"', ' ', ')', ' ',
                ],
                [
                    Token::T_WHITESPACE, Token::T_RULE, Token::T_WHITESPACE, Token::T_OPEN_PARENTHESES,
                    Token::T_WHITESPACE, Token::T_TRUE, Token::T_WHITESPACE, Token::T_COMMA_SEPARATOR,
                    Token::T_WHITESPACE, Token::T_ARGUMENT, Token::T_WHITESPACE,
                    Token::T_CLOSE_PARENTHESES, Token::T_WHITESPACE, Token::T_SEPARATOR,
                    Token::T_WHITESPACE, Token::T_RULE, Token::T_WHITESPACE, Token::T_OPEN_PARENTHESES,
                    Token::T_WHITESPACE, Token::T_FALSE, Token::T_WHITESPACE, Token::T_COMMA_SEPARATOR,
                    Token::T_WHITESPACE, Token::T_NULL, Token::T_WHITESPACE, Token::T_COMMA_SEPARATOR,
                    Token::T_WHITESPACE, Token::T_QUOTE, Token::T_ARGUMENT, Token::T_QUOTE, Token::T_WHITESPACE,
                    Token::T_CLOSE_PARENTHESES, Token::T_WHITESPACE,
                ],
            ],
            // 7
            [
                ' required ( true , 100 ) ',
                13,
                [
                    ' ', 'required', ' ', '(', ' ', 'true', ' ', ',', ' ', '100', ' ', ')', ' ',
                ],
                [
                    Token::T_WHITESPACE, Token::T_RULE, Token::T_WHITESPACE, Token::T_OPEN_PARENTHESES,
                    Token::T_WHITESPACE, Token::T_TRUE, Token::T_WHITESPACE, Token::T_COMMA_SEPARATOR,
                    Token::T_WHITESPACE, Token::T_ARGUMENT, Token::T_WHITESPACE,
                    Token::T_CLOSE_PARENTHESES, Token::T_WHITESPACE,
                ],
            ],
            // 8
            [
                ' required ( true , 100 ) |',
                14,
                [
                    ' ', 'required', ' ', '(', ' ', 'true', ' ', ',', ' ', '100', ' ', ')', ' ', '|',
                ],
                [
                    Token::T_WHITESPACE, Token::T_RULE, Token::T_WHITESPACE, Token::T_OPEN_PARENTHESES,
                    Token::T_WHITESPACE, Token::T_TRUE, Token::T_WHITESPACE, Token::T_COMMA_SEPARATOR,
                    Token::T_WHITESPACE, Token::T_ARGUMENT, Token::T_WHITESPACE,
                    Token::T_CLOSE_PARENTHESES, Token::T_WHITESPACE, Token::T_SEPARATOR,
                ],
            ],
            // 9
            [
                'required("\'123\'")',
                6,
                [
                    'required', '(', '"', '\'123\'', '"', ')',
                ],
                [
                    Token::T_RULE, Token::T_OPEN_PARENTHESES, Token::T_QUOTE, Token::T_ARGUMENT,
                    Token::T_QUOTE, Token::T_CLOSE_PARENTHESES,
                ],
            ],
            // 10
            [
                'required(\'"123"\')',
                6,
                [
                    'required', '(', '\'', '"123"', '\'', ')',
                ],
                [
                    Token::T_RULE, Token::T_OPEN_PARENTHESES, Token::T_QUOTE, Token::T_ARGUMENT,
                    Token::T_QUOTE, Token::T_CLOSE_PARENTHESES,
                ],
            ],
            // 11
            [
                'required(true,false)',
                6,
                [
                    'required', '(', 'true', ',', 'false', ')',
                ],
                [
                    Token::T_RULE, Token::T_OPEN_PARENTHESES, Token::T_TRUE, Token::T_COMMA_SEPARATOR, Token::T_FALSE,
                    Token::T_CLOSE_PARENTHESES,
                ],
            ],
            // 12
            [
                'required(100,200)',
                6,
                [
                    'required', '(', '100', ',', '200', ')',
                ],
                [
                    Token::T_RULE, Token::T_OPEN_PARENTHESES, Token::T_ARGUMENT, Token::T_COMMA_SEPARATOR,
                    Token::T_ARGUMENT, Token::T_CLOSE_PARENTHESES,
                ],
            ],
        ];
    }

    /**
     * Тестирование лексического анализатора
     *
     * @param string[] $images
     * @param int[] $types
     *
     * @dataProvider dataTokenizer
     */
    public function testTokenizer(string $source, int $count, array $images, array $types): void
    {
        $tokenizer = new Tokenizer($source);
        $imagesEquals = [];
        $typesEquals = [];
        $image = '';
        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            $imagesEquals[] = $token->getImage();
            $typesEquals[] = $token->getType();
            $image .= $token->getImage();
        }

        $this->assertEquals($images, $imagesEquals);
        $this->assertEquals($types, $typesEquals);
        $this->assertEquals($source, $image);
        $this->assertEquals($count, $tokenizer->getCount());
    }
}
