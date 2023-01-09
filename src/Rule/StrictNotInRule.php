<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

/**
 * Не допустимые значения (строгая проверка)
 */
class StrictNotInRule extends NotInRule
{
    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->getPresence()->isPresence($value, $this->values)) {
            return true;
        }

        $success = !$this->notIn->hasValue($value->getValue());

        if (!$success) {
            $this->addMessage(
                '{{if(name)}}Для "{{name}}" не{{else}}Не{{endif}} разрешены значения {{notIn}}',
                'strictNotIn'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'strictNotIn';
    }
}
