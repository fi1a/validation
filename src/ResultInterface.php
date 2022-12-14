<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Результат валидации
 */
interface ResultInterface
{
    /**
     * Результат валидации
     */
    public function isSuccess(): ?bool;

    /**
     * Установить результат валидации
     */
    public function setSuccess(bool $success): bool;

    /**
     * Добавить ошибку
     */
    public function addError(ErrorInterface $error): bool;

    /**
     * Добавить ошибки
     *
     * @param ErrorInterface[]|Errors $errors
     */
    public function addErrors($errors): bool;

    /**
     * Возвращает ошибки
     */
    public function getErrors(): Errors;

    /**
     * Очищает все ошибки
     */
    public function clearErrors(): bool;

    /**
     * Возвращает данные участвующие в проверке
     */
    public function getValues(): ResultValuesInterface;

    /**
     * Устанавливает данные участвующие в проверке
     */
    public function setValues(ResultValuesInterface $values): bool;
}
