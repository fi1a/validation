<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;

/**
 * Является ли значение null
 */
class NullRule extends ARule
{
    /**
     * @inheritDoc
     */
    public function validate(IValue $value): bool
    {
        $success = is_null($value->getValue());

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}не является пустым', 'isNull');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'null';
    }
}
