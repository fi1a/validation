<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;

/**
 * Обязательное значение
 */
class RequiredRule extends ARule
{
    /**
     * @inheritDoc
     */
    public function validate(IValue $value): bool
    {
        $success = !is_null($value->getValue()) && $value->getValue() !== '' && $value->getValue() !== false;

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}является обязательным', 'required');
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
