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
        $success = !is_null($value) && $value !== '' && $value !== false;

        if (!$success) {
            $this->addMessage('Field "{{name}}" is required', 'required');
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
