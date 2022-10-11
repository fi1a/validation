<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Ошибки
 *
 * @method ErrorInterface first()
 * @method ErrorInterface last()
 * @method ErrorInterface delete($key)
 * @method ErrorInterface put($key, $value)
 * @method ErrorInterface putIfAbsent($key, $value)
 * @method ErrorInterface replace($key, $value)
 * @method ErrorInterface[] column(string $name)
 */
interface ErrorsInterface
{
    /**
     * Возвращает первую ошибку для поля
     */
    public function firstOfAll(): ErrorsInterface;

    /**
     * Возвращает все ошибки для конкретного поля
     */
    public function allForField(string $fieldName): ErrorsInterface;

    /**
     * Возвращает первую ошибку для конкретного поля
     */
    public function firstOfField(string $fieldName): ?ErrorInterface;

    /**
     * Возвращает все ошибки для конкретного правила
     */
    public function allForRule(string $ruleName): ErrorsInterface;

    /**
     * Возвращает массив с ошибками
     *
     * @return string[]
     */
    public function asArray(bool $flat = true): array;
}
