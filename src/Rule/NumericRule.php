<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

/**
 * Является ли значение числом
 */
class NumericRule extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->presence->isPresence($value, $this->values)) {
            return true;
        }

        $success = is_numeric($value->getValue());

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}не является числом', 'numeric');
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
