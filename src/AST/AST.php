<?php

declare(strict_types=1);

namespace Fi1a\Validation\AST;

use Fi1a\Tokenizer\IToken;
use Fi1a\Tokenizer\ITokenizer;
use Fi1a\Validation\AST\Exception\ParseRuleException;
use Fi1a\Validation\Tokenizer\Token;
use Fi1a\Validation\Tokenizer\Tokenizer;

/**
 * AST
 */
class AST implements IAST
{
    /**
     * @var IRules
     */
    private $rules;

    /**
     * @inheritDoc
     */
    public function __construct(string $string)
    {
        $this->rules = new Rules();
        $tokenizer = new Tokenizer($string);
        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            /** @psalm-suppress PossiblyInvalidMethodCall */
            if ($token->getType() === Token::T_RULE) {
                /** @psalm-suppress PossiblyInvalidArgument */
                $this->rule($tokenizer, $token);

                continue;
            }
        }
    }

    /**
     * Переменная
     *
     * @param mixed[] $values
     */
    private function rule(Tokenizer $tokenizer, IToken $tokenRule): void
    {
        $ruleName = $tokenRule->getImage();
        $arguments = [];
        if ($tokenizer->lookAtNextType() === Token::T_WHITESPACE) {
            $tokenizer->next();
        }
        if ($tokenizer->lookAtNextType() === Token::T_OPEN_PARENTHESES) {
            $tokenizer->next();
            if ($tokenizer->lookAtNextType() === Token::T_WHITESPACE) {
                $tokenizer->next();
            }
            do {
                $isSingle = false;
                $isQuote = false;
                $token = $tokenizer->next();
                if ($token === ITokenizer::T_EOF) {
                    throw new ParseRuleException(
                        sprintf(
                            'Rule format error (%d:%d)',
                            $tokenRule->getEndLine(),
                            $tokenRule->getEndColumn()
                        )
                    );
                }
                /** @psalm-suppress PossiblyInvalidMethodCall */
                if ($token->getType() === Token::T_WHITESPACE) {
                    $token = $tokenizer->next();
                    if ($token === ITokenizer::T_EOF) {
                        throw new ParseRuleException(
                            sprintf(
                                'Rule format error (%d:%d)',
                                $tokenRule->getEndLine(),
                                $tokenRule->getEndColumn()
                            )
                        );
                    }
                }
                /** @psalm-suppress PossiblyInvalidMethodCall */
                if ($token->getType() === Token::T_CLOSE_PARENTHESES && !count($arguments)) {
                    break;
                }
                /** @psalm-suppress PossiblyInvalidMethodCall */
                if ($token->getType() === Token::T_QUOTE) {
                    $isQuote = $token->getImage() === '"';
                    $isSingle = $token->getImage() === "'";
                    $token = $tokenizer->next();
                    if ($token === ITokenizer::T_EOF) {
                        throw new ParseRuleException(
                            sprintf(
                                'Rule format error (%d:%d)',
                                $tokenRule->getEndLine(),
                                $tokenRule->getEndColumn()
                            )
                        );
                    }
                }
                /** @psalm-suppress PossiblyInvalidMethodCall */
                if (
                    !in_array(
                        $token->getType(),
                        [Token::T_ARGUMENT, Token::T_TRUE, Token::T_FALSE, Token::T_NULL, Token::T_QUOTE,]
                    )
                    && $token->getType() !== Token::T_CLOSE_PARENTHESES
                ) {
                    throw new ParseRuleException(
                        sprintf(
                            'Rule syntax error (%d:%d)',
                            $tokenRule->getEndLine(),
                            $tokenRule->getEndColumn()
                        )
                    );
                }

                /** @psalm-suppress PossiblyInvalidMethodCall */
                $value = $token->getImage();
                /** @psalm-suppress PossiblyInvalidMethodCall */
                if ($token->getType() === Token::T_QUOTE && ($isQuote || $isSingle)) {
                    $value = '';
                }
                /** @psalm-suppress PossiblyInvalidMethodCall */
                if ($token->getType() === Token::T_TRUE) {
                    $value = true;
                } elseif ($token->getType() === Token::T_FALSE) {
                    $value = false;
                } elseif ($token->getType() === Token::T_NULL) {
                    $value = null;
                } elseif ($isQuote) {
                    $value = str_replace('\"', '"', $value);
                } elseif ($isSingle) {
                    $value = str_replace("\'", "'", $value);
                } elseif (is_numeric($value) && stripos($value, '.') !== false) {
                    $value = (float) $value;
                } elseif (is_numeric($value) && stripos($value, '.') === false) {
                    $value = (int) $value;
                }

                $arguments[] = new Argument($value);

                /** @psalm-suppress PossiblyInvalidMethodCall */
                if ($token->getType() !== Token::T_QUOTE) {
                    $token = $tokenizer->next();
                }
                /** @psalm-suppress PossiblyInvalidMethodCall */
                if (
                    $token === ITokenizer::T_EOF
                    || ($isQuote && $token->getImage() !== '"')
                    || ($isSingle && $token->getImage() !== '\'')
                ) {
                    throw new ParseRuleException(
                        sprintf(
                            'Quote syntax error (%d:%d)',
                            $tokenRule->getEndLine(),
                            $tokenRule->getEndColumn()
                        )
                    );
                }
                /** @psalm-suppress PossiblyInvalidMethodCall */
                if ($token->getType() === Token::T_QUOTE) {
                    $token = $tokenizer->next();
                    if ($token === ITokenizer::T_EOF) {
                        throw new ParseRuleException(
                            sprintf(
                                'Rule syntax error (%d:%d)',
                                $tokenRule->getEndLine(),
                                $tokenRule->getEndColumn()
                            )
                        );
                    }
                }
                /** @psalm-suppress PossiblyInvalidMethodCall */
                if ($token->getType() === Token::T_WHITESPACE) {
                    $token = $tokenizer->next();
                    if ($token === ITokenizer::T_EOF) {
                        throw new ParseRuleException(
                            sprintf(
                                'Rule format error (%d:%d)',
                                $tokenRule->getEndLine(),
                                $tokenRule->getEndColumn()
                            )
                        );
                    }
                }
                /** @psalm-suppress PossiblyInvalidMethodCall */
                $loop = $token !== ITokenizer::T_EOF && $token->getType() !== Token::T_CLOSE_PARENTHESES;
                /** @psalm-suppress PossiblyInvalidMethodCall */
                if ($loop && $token->getType() !== Token::T_COMMA_SEPARATOR) {
                    throw new ParseRuleException(
                        sprintf(
                            'Comma separator syntax error (%d:%d)',
                            $tokenRule->getEndLine(),
                            $tokenRule->getEndColumn()
                        )
                    );
                }
            } while ($loop);
        }
        $this->rules[] = new Rule($ruleName, $arguments);
    }

    /**
     * @inheritDoc
     */
    public function getRules(): IRules
    {
        return $this->rules;
    }
}
