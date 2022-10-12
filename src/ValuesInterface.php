<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Интерфейс значений
 */
interface ValuesInterface
{
    public const PATH_SEPARATOR = ':';

    /**
     * Конструктор
     *
     * @param mixed $values
     */
    public function __construct($values);

    /**
     * Возвращает значение поля
     *
     * @return ValueInterface|ValueInterface[]
     */
    public function getValue(string $fieldName);

    /**
     * Возвращает значения
     *
     * @return mixed
     */
    public function getRaw();

    /**
     * Значение как массив
     */
    public function asArray(): bool;

    /**
     * Установить флаг значения как массива
     */
    public function setAsArray(bool $asArray): bool;
}
