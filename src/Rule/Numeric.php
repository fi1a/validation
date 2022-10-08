<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;

/**
 * Является ли значение числом
 */
class Numeric extends ARule
{
    /**
     * @inheritDoc
     */
    public function validate(IValue $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }

        $success = is_numeric($value->getValue());

        if (!$success) {
            $this->addMessage('The field {{if(name)}}"{{name}}" {{endif}}must be numeric', 'numeric');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'numeric';
    }
}
