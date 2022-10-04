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
     * @param IRule[] $rules
     */
    public function __construct(array $rules = []);
}
