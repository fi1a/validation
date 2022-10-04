<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;

/**
 * Цепочка правил валидатора
 */
interface IChain
{
    /**
     * Конструктор
     *
     * @param IRule[]|IChain[] $rules
     */
    public function __construct(array $rules = []);

    /**
     * Метод валидации
     *
     * @param mixed $values
     */
    public function validate($values, ?string $field = null): bool;

    /**
     * Возвращает правила
     *
     * @return IRule[]|IChain[]
     */
    public function getRules(): array;

    /**
     * Устанавливает правила
     *
     * @param IRule[]|IChain[] $rules
     */
    public function setRules(array $rules): bool;
}
