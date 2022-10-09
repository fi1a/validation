<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Fixtures;

use Fi1a\Validation\Rule\AbstractRule;

/**
 * Пустое название правила валидации
 */
class EmptyRuleName extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        $this->messages[] = 'Error';

        return false;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return '';
    }
}
