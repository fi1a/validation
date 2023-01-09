<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

use const FILTER_VALIDATE_URL;

/**
 * Является ли значение url
 */
class UrlRule extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->getPresence()->isPresence($value, $this->values)) {
            return true;
        }

        $success = filter_var((string) $value->getValue(), FILTER_VALIDATE_URL) !== false;

        if (!$success) {
            $this->addMessage(
                'Значение {{if(name)}}"{{name}}" {{endif}}не является url',
                'url'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'url';
    }
}
