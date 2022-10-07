<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;

/**
 * Обязательное значение
 */
class Required extends ARule
{
    /**
     * @inheritDoc
     */
    public function validate(IValue $value): bool
    {
        $success = !is_null($value->getValue()) && $value->getValue() !== '' && $value->getValue() !== false;

        if (!$success) {
            $this->addMessage('Field "{{name}}" is required', 'required');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'required';
    }
}
