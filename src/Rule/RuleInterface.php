<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

/**
 * Правило валидации
 */
interface RuleInterface
{
    /**
     * Метод валидации
     */
    public function validate(ValueInterface $value): bool;

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
