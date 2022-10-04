<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;

/**
 * Все правила должны удовлетворять условию
 */
class AllOf extends AChain
{
    /**
     * @inheritDoc
     */
    public function validate(array $values): bool
    {
        $result = true;
        foreach ($this->getRules() as $field => $rule) {
            if ($rule instanceof IRule) {
                /**
                 * @var mixed $value
                 */
                $value = $values[$field] ?? null;
                $validate = $rule->validate($value);
                $result = $result && $validate;

                continue;
            }

            $validate = $rule->validate($values);
            $result = $result && $validate;
        }

        return $result;
    }
}
