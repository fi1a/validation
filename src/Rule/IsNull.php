<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;

/**
 * Является ли значение null
 */
class IsNull extends ARule
{
    /**
     * @inheritDoc
     */
    public function validate(IValue $value): bool
    {
        $success = is_null($value->getValue());

        if (!$success) {
            $this->addMessage('Field {{if(name)}}"{{name}}" {{endif}}not is null', 'isNull');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'isNull';
    }
}
