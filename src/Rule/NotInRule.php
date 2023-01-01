<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Collection\DataType\MapArrayObject;
use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;
use InvalidArgumentException;

/**
 * Не допустимые значения
 */
class NotInRule extends AbstractRule
{
    /**
     * @var MapArrayObject
     */
    protected $notIn;

    /**
     * Конструктор
     *
     * @param WhenPresenceInterface|mixed|null  $presence
     * @param mixed ...$notIn
     */
    public function __construct($presence = null, ...$notIn)
    {
        if (!($presence instanceof WhenPresenceInterface) && $presence !== null) {
            array_unshift($notIn, $presence);
            $presence = null;
        }
        if (count($notIn) === 1 && is_array($notIn[0])) {
            $notIn = $notIn[0];
        }
        $this->notIn = new MapArrayObject($notIn);
        if ($this->notIn->isEmpty()) {
            throw new InvalidArgumentException('Не переданы значения $notIn');
        }
        parent::__construct($presence);
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->presence->isPresence($value, $this->values)) {
            return true;
        }

        $notIn = new MapArrayObject($this->notIn->getArrayCopy());
        /**
         * @var mixed $check
         */
        $check = $value->getValue();
        if (is_string($check)) {
            $check = mb_strtolower($check);
        }

        $success = !$notIn->map(function ($value) {
            if (is_string($value)) {
                $value = mb_strtolower($value);
            }

            return $value;
        })->hasValue($check);

        if (!$success) {
            $this->addMessage(
                '{{if(name)}}Для "{{name}}" не{{else}}Не{{endif}} разрешены значения {{notIn}}',
                'notIn'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(
            parent::getVariables(),
            ['notIn' => $this->notIn->wraps('"')->join(', ')]
        );
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'notIn';
    }
}
