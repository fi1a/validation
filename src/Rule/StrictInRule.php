<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

/**
 * Допустимые значения (строгая проверка)
 */
class StrictInRule extends InRule
{
    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->getPresence()->isPresence($value, $this->values)) {
            return true;
        }

        $success = $this->in->hasValue($value->getValue());

        if (!$success) {
            $this->addMessage(
                '{{if(name)}}Для "{{name}}" р{{else}}Р{{endif}}азрешены только значения {{in}}',
                'strictIn'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'strictIn';
    }
}
