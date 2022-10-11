<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Значение
 */
interface ValueInterface
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
    public function isWildcard(): bool;

    /**
     * Установить флаг определяющий является значение массивом или нет
     */
    public function setWildcard(bool $wildcard): bool;

    /**
     * Значение из массива
     */
    public function isWildcardItem(): bool;

    /**
     * Установить флаг определяющий является значение из массива или нет
     */
    public function setWildcardItem(bool $wildcardItem): bool;

    /**
     * Устанавливает путь
     */
    public function setPath(string $path): bool;

    /**
     * Возвращает путь
     */
    public function getPath(): ?string;

    /**
     * Устанавливает путь
     */
    public function setWildcardPath(string $path): bool;

    /**
     * Возвращает путь
     */
    public function getWildcardPath(): ?string;

    /**
     * Наличие значения
     */
    public function setPresence(bool $presence): bool;

    /**
     * Возвращает наличие значения
     */
    public function isPresence(): bool;

    /**
     * Установить название правила
     */
    public function setRuleName(string $ruleName): bool;

    /**
     * Вернуть название правила
     */
    public function getRuleName(): ?string;

    /**
     * Валидное значение для правила или нет
     */
    public function isValid(): ?bool;

    /**
     * Установить флаг валидности значения для правила
     */
    public function setValid(bool $valid): bool;
}
