<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;

/**
 * Набор правил
 */
interface IRuleSet
{
    /**
     * Конструктор
     *
     * @param mixed $values
     */
    public function __construct($values, ?string $scenario = null);

    /**
     * Установить значения
     *
     * @param mixed $values
     */
    public function setValues($values): IRuleSet;

    /**
     *  Возвращает значения
     *
     * @return mixed
     */
    public function getValues();

    /**
     * Установить сценарий
     */
    public function setScenario(?string $scenario): IRuleSet;

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
     * @return IRule[]|IChain[]
     */
    public function getRules(): array;

    /**
     * Инициализация
     */
    public function init(): bool;

    /**
     * Возвращает цепочку для объявления правил
     */
    public function fields(string ...$fields): IFieldsChain;
}
