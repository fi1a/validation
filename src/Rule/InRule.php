<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Collection\DataType\MapArrayObject;
use Fi1a\Validation\ValueInterface;

/**
 * Разрешенные значения
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
        $this->in = new MapArrayObject($in);
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }

        $success = $this->in->hasValue($value->getValue());

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
