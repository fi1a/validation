<?php

declare(strict_types=1);

namespace Fi1a\Validation\AST;

/**
 * Правило
 */
interface IRule
{
    /**
     * Конструктор
     *
     * @param IArgument[]  $arguments
     */
    public function __construct(string $ruleName, array $arguments);

    /**
     * Возвращает название правила
     */
    public function getRuleName(): string;

    /**
     * Возвращает аргументы правила
     *
     * @return IArgument[]
     */
    public function getArguments(): array;

    /**
     * Возвращает значения аргументов правила
     *
     * @return string[]|int[]|null[]|bool[]
     */
    public function getArgumentsValues(): array;
}
