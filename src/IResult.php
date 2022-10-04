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
     * Добавить ошибку
     */
    public function addError(IError $error): bool;

    /**
     * Добавить ошибки
     *
     * @param IError[] $errors
     */
    public function addErrors(array $errors): bool;

    /**
     * Возвращает ошибки
     *
     * @return IError[]
     */
    public function getErrors(): array;
}
