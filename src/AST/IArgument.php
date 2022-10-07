<?php

declare(strict_types=1);

namespace Fi1a\Validation\AST;

/**
 * Аргумент правила
 */
interface IArgument
{
    /**
     * Конструктор
     *
     * @param bool|int|float|string|null $value
     */
    public function __construct($value);

    /**
     * Возвращает значение
     *
     * @return bool|int|string|null
     */
    public function getValue();
}
