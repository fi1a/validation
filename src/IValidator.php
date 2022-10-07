<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;

/**
 * Валидатор
 */
interface IValidator
{
    /**
     * Конструктор
     *
     * @param string[] $messages
     */
    public function __construct(array $messages = []);

    /**
     * Создать класс проверки значений
     *
     * @param mixed[] $values
     * @param string[]|IRule[][]|IRule[]|IChain[]|null $rules
     * @param string[] $messages
     * @param string[] $titles
     */
    public function make(
        array $values,
        ?array $rules = null,
        array $messages = [],
        array $titles = []
    ): IValidation;

    /**
     * Добавить правило валидации
     */
    public static function addRule(string $ruleClass): bool;

    /**
     * Проверяет наличие правила валидации
     */
    public static function hasRule(string $ruleClass): bool;

    /**
     * Возвращает правило по названию
     */
    public static function getRuleClassByName(string $ruleName): string;
}
