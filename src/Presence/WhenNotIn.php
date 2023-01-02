<?php

declare(strict_types=1);

namespace Fi1a\Validation\Presence;

use Fi1a\Collection\Helpers\ArrayHelper;
use Fi1a\Validation\ValueInterface;
use Fi1a\Validation\ValuesInterface;

/**
 * Определяет по переданным значениям присутсвует значение или нет
 */
class WhenNotIn implements WhenPresenceInterface
{
    /**
     * @var mixed[]
     */
    private $notPresenceValues;

    /**
     * @param mixed ...$notPresenceValues
     */
    public function __construct(...$notPresenceValues)
    {
        if (count($notPresenceValues) === 1 && is_array($notPresenceValues[0])) {
            $notPresenceValues = $notPresenceValues[0];
        }
        $this->notPresenceValues = $notPresenceValues;
    }

    /**
     * @inheritDoc
     */
    public function isPresence(ValueInterface $value, ?ValuesInterface $values): bool
    {
        /** @var mixed $check */
        $check = $value->getValue();
        if (is_string($check)) {
            $check = mb_strtolower($check);
        }

        return !ArrayHelper::hasValue(ArrayHelper::map($this->notPresenceValues, function ($value) {
            if (is_string($value)) {
                $value = mb_strtolower($value);
            }

            return $value;
        }), $check);
    }
}
