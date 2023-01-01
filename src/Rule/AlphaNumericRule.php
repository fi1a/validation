<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

/**
 * Значение должно быть буквенно-цифровым
 */
class AlphaNumericRule extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->presence->isPresence($value, $this->values)) {
            return true;
        }
        $success = is_string($value->getValue()) || is_numeric($value->getValue());
        $success = $success && preg_match('/^[\pL\pM\pN]+$/mu', (string) $value->getValue()) > 0;

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
