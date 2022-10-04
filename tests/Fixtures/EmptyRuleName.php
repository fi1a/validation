<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Fixtures;

use Fi1a\Validation\Rule\ARule;

/**
 * Пустое название правила валидации
 */
class EmptyRuleName extends ARule
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
    public function getRuleName(): string
    {
        return '';
    }
}
