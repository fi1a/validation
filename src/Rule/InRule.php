<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Collection\DataType\MapArrayObject;
use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;
use InvalidArgumentException;

/**
 * Допустимые значения
 */
class InRule extends AbstractRule
{
    /**
     * @var MapArrayObject
     */
    protected $in;

    /**
     * Конструктор
     *
     * @param WhenPresenceInterface|mixed|null  $presence
     * @param mixed ...$in
     */
    public function __construct($presence = null, ...$in)
    {
        if (!($presence instanceof WhenPresenceInterface) && $presence !== null) {
            array_unshift($in, $presence);
            $presence = null;
        }
        if (count($in) === 1 && is_array($in[0])) {
            $in = $in[0];
        }
        $this->in = new MapArrayObject($in);
        if ($this->in->isEmpty()) {
            throw new InvalidArgumentException('Не переданы значения $in');
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

        $in = new MapArrayObject($this->in->getArrayCopy());
        /**
         * @var mixed $check
         */
        $check = $value->getValue();
        if (is_string($check)) {
            $check = mb_strtolower($check);
        }
        $success = $in->map(function ($value) {
            if (is_string($value)) {
                $value = mb_strtolower($value);
            }

            return $value;
        })->hasValue($check);

        if (!$success) {
            $this->addMessage(
                '{{if(name)}}Для "{{name}}" р{{else}}Р{{endif}}азрешены только значения {{in}}',
                'in'
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
            ['in' => $this->in->wraps('"')->join(', ')]
        );
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'in';
    }
}
