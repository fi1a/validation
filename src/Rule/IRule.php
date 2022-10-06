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

    /**
     * Возвращает название правила
     */
    public static function getRuleName(): string;

    /**
     * Возвращает сообщения об ошибках
     *
     * @return string[]
     */
    public function getMessages(): array;

    /**
     * Возвращает значения для подстановки в сообщения
     *
     * @return mixed[]
     */
    public function getVariables(): array;
}
