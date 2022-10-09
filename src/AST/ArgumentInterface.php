<?php

declare(strict_types=1);

namespace Fi1a\Validation\AST;

/**
 * Аргумент правила
 */
interface ArgumentInterface
{
    /**
     * Конструктор
     *
     * @param scalar|null $value
     */
    public function __construct($value);

    /**
     * Возвращает значение
     *
     * @return scalar|null
     */
    public function getValue();
}
