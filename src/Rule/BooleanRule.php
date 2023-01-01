<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

/**
 * Является ли значение логическим
 */
class BooleanRule extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->presence->isPresence($value, $this->values)) {
            return true;
        }

        /**
         * @var mixed $value
         */
        $value = $value->getValue();
        if (is_string($value)) {
            $value = mb_strtolower($value);
        }
        $success = in_array($value, [true, false, 'y', 'n', '0', '1', 0, 1, 'true', 'false'], true);

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}должно быть логическим', 'boolean');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'boolean';
    }
}
