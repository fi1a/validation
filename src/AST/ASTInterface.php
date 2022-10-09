<?php

declare(strict_types=1);

namespace Fi1a\Validation\AST;

/**
 * Интерфейс AST
 */
interface ASTInterface
{
    /**
     * Конструктор
     */
    public function __construct(string $string);

    /**
     * Возвращает список правил
     */
    public function getRules(): RulesInterface;
}
