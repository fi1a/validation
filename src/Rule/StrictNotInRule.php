<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Collection\DataType\MapArrayObject;
use Fi1a\Validation\ValueInterface;
use InvalidArgumentException;

/**
 * Не допустимые значения (строгая проверка)
 */
class StrictNotInRule extends AbstractRule
{
    /**
     * @var MapArrayObject
     */
    private $notIn;

    /**
     * Конструктор
     *
     * @param mixed ...$notIn
     */
    public function __construct(...$notIn)
    {
        if (count($notIn) === 1 && is_array($notIn[0])) {
            $notIn = $notIn[0];
        }
        $this->notIn = new MapArrayObject($notIn);
        if ($this->notIn->isEmpty()) {
            throw new InvalidArgumentException('Не переданы значения $notIn');
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

        $success = !$this->notIn->hasValue($value->getValue());

        if (!$success) {
            $this->addMessage(
                '{{if(name)}}Для "{{name}}" не{{else}}Не{{endif}} разрешены значения {{notIn}}',
                'strictNotIn'
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
        return 'strictNotIn';
    }
}
