<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Результат валидации
 */
interface IResult
{
    /**
     * Результат валидации
     */
    public function isSuccess(): bool;

    /**
     * Установить результат валидации
     */
    public function setSuccess(bool $success): bool;
}
