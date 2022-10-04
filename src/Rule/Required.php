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
        $success = (bool) $value;

        if (!$success) {
            $this->addMessage('Field "{{fieldName}}" is required');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getRuleName(): string
    {
        return 'required';
    }
}
