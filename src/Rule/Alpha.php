<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;

/**
 * Является ли значение строкой без чисел
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
            && preg_match('/^[\pL\pM]+$/mu', (string) $value->getValue()) > 0;

        if (!$success) {
            $this->addMessage(
                'В значениии {{if(name)}}"{{name}}" {{endif}}разрешены только символы алфавита',
                'alpha'
            );
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