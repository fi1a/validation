<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Fixtures;

use Fi1a\Validation\Rule\AbstractRule;

/**
 * Исключение при вызове getValue
 */
class EmptyValues extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        $this->getValue('fieldName');

        return false;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'emptyValues';
    }
}
