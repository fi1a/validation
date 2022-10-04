<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

/**
 * Обязательное значение
 */
class Required extends ARule
{
    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        return (bool) $value;
    }
}
