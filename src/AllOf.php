<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;

/**
 * Все правила должны удовлетворять условию
 */
class AllOf implements IChain
{
    /**
     * @var IRule[]
     */
    private $rules = [];

    /**
     * @inheritDoc
     */
    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }
}
