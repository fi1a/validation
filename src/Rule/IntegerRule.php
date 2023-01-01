<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

use const FILTER_VALIDATE_INT;

/**
 * Является ли значение целым числом
 */
class IntegerRule extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->presence->isPresence($value, $this->values)) {
            return true;
        }

        $success = filter_var($value->getValue(), FILTER_VALIDATE_INT) !== false;

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}не является целым числом', 'integer');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'integer';
    }
}
