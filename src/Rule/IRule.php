<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

/**
 * Правило валидации
 */
interface IRule
{
    /**
     * Метод валидации
     *
     * @param mixed $value
     */
    public function validate($value): bool;
}
