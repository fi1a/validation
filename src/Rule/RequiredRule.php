<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

/**
 * Обязательное значение
 */
class RequiredRule extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (is_array($value->getValue())) {
            /** @psalm-suppress MixedArgument */
            $success = count($value->getValue()) > 0;
        } else {
            $success = !is_null($value->getValue()) && $value->getValue() !== '' && $value->getValue() !== false;
        }

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}является обязательным', 'required');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'required';
    }
}
