<?php

declare(strict_types=1);

namespace Fi1a\Validation\Presence;

use Fi1a\Validation\ValueInterface;
use Fi1a\Validation\ValuesInterface;

/**
 * Определяет по переданному значению присутсвует значение или нет
 */
class WhenNotValue implements WhenPresenceInterface
{
    /**
     * @var mixed
     */
    private $notPresenceValue;

    /**
     * @param mixed $notPresenceValue
     */
    public function __construct($notPresenceValue)
    {
        $this->notPresenceValue = $notPresenceValue;
    }

    /**
     * @inheritDoc
     */
    public function isPresence(ValueInterface $value, ?ValuesInterface $values): bool
    {
        return $value->getValue() !== $this->notPresenceValue;
    }
}
