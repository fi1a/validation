<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;

/**
 * Валидатор
 */
class Validator implements IValidator
{
    /**
     * @inheritDoc
     */
    public function __construct(array $messages = [])
    {
    }

    /**
     * @inheritDoc
     */
    public function make($values, ?array $rules = null, array $messages = []): IValidation
    {
        /**
         * @var IRule[] $ruleInstances
         */
        $ruleInstances = [];
        $chain = new AllOf($ruleInstances);

        return new Validation($this, $values, $chain, $messages);
    }
}
