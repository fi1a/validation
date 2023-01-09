<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\RuleInterface;

/**
 * Валидатор
 */
interface ValidatorInterface
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
     * @param mixed                                                            $values
     * @param string[]|RuleInterface[][]|RuleInterface[]|ChainInterface[]|null $rules
     * @param string[]                                                         $messages
     * @param string[]                                                         $titles
     */
    public function make(
        $values,
        ?array $rules = null,
        array $messages = [],
        array $titles = [],
        ?string $scenario = null
    ): ValidationInterface;

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
