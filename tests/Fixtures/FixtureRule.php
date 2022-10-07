<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Fixtures;

use Fi1a\Validation\Rule\ARule;

/**
 * Тестирование методов добавления правил
 */
class FixtureRule extends ARule
{
    private $variables = [];

    /**
     * Конструктор
     *
     * @param null    $null
     */
    public function __construct(bool $bool1, bool $bool2, $null, int $int, float $float, string $string)
    {
        $this->variables = [
            'bool1' => $bool1,
            'bool2' => $bool2,
            'null' => $null,
            'float' => $float,
            'int' => $int,
            'string' => $string,
        ];
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        $this->addMessage('Error', 'fixtureRule');

        return false;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'fixtureRule';
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), $this->variables);
    }
}
