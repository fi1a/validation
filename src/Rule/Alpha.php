<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;

/**
 * Является ли значение строкой
 */
class Alpha extends ARule
{
    /**
     * @inheritDoc
     */
    public function validate(IValue $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }

        $success = is_string($value->getValue())
            && preg_match('/^[\pL\pM]+$/mu', (string) $value->getValue());

        if (!$success) {
            $this->addMessage('The {{if(name)}}"{{name}}" {{endif}}only allows alphabet characters', 'alpha');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'alpha';
    }
}
