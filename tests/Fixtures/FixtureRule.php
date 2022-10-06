<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Fixtures;

use Fi1a\Validation\Rule\ARule;

/**
 * Тестирование методов добавления правил
 */
class FixtureRule extends ARule
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
        return 'fixtureRule';
    }
}
