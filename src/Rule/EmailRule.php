<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;

use const FILTER_VALIDATE_EMAIL;

/**
 * Является ли значение email
 */
class EmailRule extends ARule
{
    /**
     * @inheritDoc
     */
    public function validate(IValue $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }

        $success = filter_var((string) $value->getValue(), FILTER_VALIDATE_EMAIL) !== false;

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}не является email адресом', 'email');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'email';
    }
}
