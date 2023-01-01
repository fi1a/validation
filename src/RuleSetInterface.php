<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Набор правил
 */
interface RuleSetInterface
{
    /**
     * Конструктор
     *
     * @param mixed $values
     */
    public function __construct($values);

    /**
     * Установить значения
     *
     * @param mixed $values
     */
    public function setValues($values): RuleSetInterface;

    /**
     *  Возвращает значения
     */
    public function getValues(): ValuesInterface;

    /**
     * Установить сценарий
     */
    public function setScenario(?string $scenario): RuleSetInterface;

    /**
     * Возвращает текущий сценарий
     */
    public function getScenario(): ?string;

    /**
     * Возвращает сообщения
     *
     * @return string[]
     */
    public function getMessages(): array;

    /**
     * Возвращает заголовки полей
     *
     * @return string[]
     */
    public function getTitles(): array;

    /**
     * Возвращает набор правил
     *
     * @return OnInterface[]
     */
    public function getRules(): array;

    /**
     * Инициализация
     */
    public function init(): bool;

    /**
     * Возвращает цепочку для объявления правил
     */
    public function fields(string ...$fields): FieldsChainInterface;
}
