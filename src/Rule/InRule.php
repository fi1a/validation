<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Collection\DataType\MapArrayObject;
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
    private $in;

    /**
     * Конструктор
     *
     * @param mixed ...$in
     */
    public function __construct(...$in)
    {
        if (count($in) === 1 && is_array($in[0])) {
            $in = $in[0];
        }
        $this->in = new MapArrayObject($in);
        if ($this->in->isEmpty()) {
            throw new InvalidArgumentException('Не переданы значения $in');
        }
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$value->isPresence()) {
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
