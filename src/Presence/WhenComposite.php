<?php

declare(strict_types=1);

namespace Fi1a\Validation\Presence;

use Fi1a\Validation\ValueInterface;
use Fi1a\Validation\ValuesInterface;

/**
 * Используется как составной из других классов проверки присутсвия значения
 */
class WhenComposite implements WhenPresenceInterface
{
    /**
     * @var WhenPresenceInterface[]
     */
    private $whenPresence;

    /**
     * @inheritDoc
     */
    public function __construct(WhenPresenceInterface ...$whenPresence)
    {
        $this->whenPresence = $whenPresence;
    }

    /**
     * @inheritDoc
     */
    public function isPresence(ValueInterface $value, ?ValuesInterface $values): bool
    {
        foreach ($this->whenPresence as $whenPresence) {
            if (!$whenPresence->isPresence($value, $values)) {
                return false;
            }
        }

        return true;
    }
}
