<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

/**
 * Является ли значение null
 */
class IsNull extends ARule
{
    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        $success = is_null($value);

        if (!$success) {
            $this->addMessage('Field "{{name}}" not is null', 'isNull');
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