<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;
use InvalidArgumentException;

/**
 * Абстрактный класс цепочки правил
 */
abstract class AChain implements IChain
{
    /**
     * @var IRule[]|IChain[]
     */
    private $rules = [];

    /**
     * @inheritDoc
     */
    public function __construct(array $rules = [])
    {
        $this->setRules($rules);
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @inheritDoc
     */
    public function setRules(array $rules): bool
    {
        foreach ($rules as $rule) {
            if (!($rule instanceof IRule) && !($rule instanceof IChain)) {
                throw new InvalidArgumentException('The rule must implement the interface ' . IRule::class);
            }
        }

        $this->rules = $rules;

        return true;
    }
}
