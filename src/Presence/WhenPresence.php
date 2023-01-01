<?php

declare(strict_types=1);

namespace Fi1a\Validation\Presence;

use Fi1a\Validation\ValueInterface;
use Fi1a\Validation\ValuesInterface;

/**
 * Присутствует значение или нет
 */
class WhenPresence implements WhenPresenceInterface
{
    /**
     * @inheritDoc
     */
    public function isPresence(ValueInterface $value, ?ValuesInterface $values): bool
    {
        return $value->isPresence();
    }
}
