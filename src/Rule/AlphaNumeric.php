<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;

/**
 * Является ли значение строкой
 */
class AlphaNumeric extends ARule
{
    /**
     * @inheritDoc
     */
    public function validate(IValue $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }
        if (!is_string($value->getValue()) && !is_numeric($value->getValue())) {
            return false;
        }

        $success = preg_match('/^[\pL\pM\pN]+$/mu', (string) $value->getValue()) > 0;

        if (!$success) {
            $this->addMessage(
                'В значении {{if(name)}}"{{name}}" {{endif}}разрешены только символы алфавита и цифры',
                'alphaNumeric'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'alphaNumeric';
    }
}
