<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Fixtures;

use Fi1a\Validation\ARuleSet;

/**
 * Набор правил
 */
class FixtureRuleSet extends ARuleSet
{
    /**
     * @inheritDoc
     */
    public function init(): bool
    {
        $this->fields('key1:id', 'key1:name')->on('create')->allOf()->required();
        $this->fields('key2:*:wildcard')->on('create')->allOf()->isNull();
        $this->fields('key1:foo')->oneOf()->required()->isNull();
        $this->fields('key1:bar')->on('update')->oneOf()->required()->isNull();

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): array
    {
        $messages = parent::getMessages();

        $messages['required'] = 'Test required message {{name}}';

        return $messages;
    }

    /**
     * @inheritDoc
     */
    public function getTitles(): array
    {
        $titles = parent::getTitles();

        $titles['key1:id'] = 'ID';
        $titles['key1:name'] = 'Name';

        return $titles;
    }
}
