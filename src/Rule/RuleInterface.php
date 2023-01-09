<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;
use Fi1a\Validation\ValuesInterface;

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

    /**
     * Установить валидируемые значения
     */
    public function setValues(ValuesInterface $values): bool;

    /**
     * Установить заголовки полей
     *
     * @param string[]|null[] $titles
     */
    public function setTitles(array $titles): bool;

    /**
     * До валидации
     *
     * @param ValueInterface|ValueInterface[] $value
     *
     * @return ValueInterface|ValueInterface[]
     */
    public function beforeValidate($value);

    /**
     * До валидации
     *
     * @return ValueInterface
     */
    public function afterValidate(ValueInterface $value);

    /**
     * Установить значение объекта определяющего присутсвие
     */
    public function setPresence(?WhenPresenceInterface $presence): bool;

    /**
     * Вернуть значение объекта определяющего присутсвие
     */
    public function getPresence(): WhenPresenceInterface;
}
