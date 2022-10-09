<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

use const JSON_ERROR_NONE;

/**
 * Является ли значение json строкой
 */
class JsonRule extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }

        $success = is_string($value->getValue()) && $value->getValue() !== '';

        if ($success) {
            /** @psalm-suppress UnusedFunctionCall */
            json_decode((string) $value->getValue());
            $success = json_last_error() === JSON_ERROR_NONE;
        }

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}не является json строкой', 'json');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'json';
    }
}
