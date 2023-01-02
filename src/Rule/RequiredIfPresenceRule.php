<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

/**
 * Обязательное значение (если задано)
 */
class RequiredIfPresenceRule extends RequiredRule
{
    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->getPresence()->isPresence($value, $this->values)) {
            return true;
        }

        return parent::validate($value);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'requiredIfPresence';
    }
}
