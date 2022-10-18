<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Collection\DataType\MapArrayObject;
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
