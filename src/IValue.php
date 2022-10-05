<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Значение
 */
interface IValue
{
    /**
     * Установить значение
     *
     * @param mixed $value
     */
    public function setValue($value): bool;

    /**
     * Вернуть значение
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Значение является массивом
     */
    public function isArrayAttribute(): bool;

    /**
     * Установить флаг определяющий является значение массивом или нет
     */
    public function setArrayAttribute(bool $arrayAttribute): bool;

    /**
     * Устанавливает путь
     */
    public function setPath(string $path): bool;

    /**
     * Возвращает путь
     */
    public function getPath(): ?string;

    /**
     * Формирует список значений из дерева
     *
     * @return mixed[][]
     */
    public function flatten(): array;
}
