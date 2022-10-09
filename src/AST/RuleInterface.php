<?php

declare(strict_types=1);

namespace Fi1a\Validation\AST;

/**
 * Правило
 */
interface RuleInterface
{
    /**
     * Конструктор
     *
     * @param ArgumentInterface[] $arguments
     */
    public function __construct(string $ruleName, array $arguments);

    /**
     * Возвращает название правила
     */
    public function getRuleName(): string;

    /**
     * Возвращает аргументы правила
     *
     * @return ArgumentInterface[]
     */
    public function getArguments(): array;

    /**
     * Возвращает значения аргументов правила
     *
     * @return null[]|scalar[]
     */
    public function getArgumentsValues(): array;
}
