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
    public function validate($values, ?string $field = null): IResult
    {
        $result = new Result();
        $result->setSuccess(true);

        foreach ($this->getRules() as $key => $rule) {
            if (is_null($field)) {
                $field = (string) $key;
            }
            if ($rule instanceof IRule) {
                if (is_array($values)) {
                    /**
                     * @var mixed $value
                     */
                    $value = $values[$field] ?? null;
                } else {
                    /**
                     * @var mixed $value
                     */
                    $value = $values;
                }
                $validate = $rule->validate($value);
                $result->setSuccess($result->isSuccess() && $validate);

                continue;
            }

            $validate = $rule->validate($values, $field);
            $result->setSuccess($result->isSuccess() && $validate->isSuccess());
        }

        return $result;
    }
}
