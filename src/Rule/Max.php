<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;
use InvalidArgumentException;

/**
 * Проверка на максимальное значение
 */
class Max extends ARule
{
    /**
     * @var int|float
     */
    private $max;

    /**
     * Конструктор
     *
     * @param float|int $max
     */
    public function __construct($max)
    {
        /** @psalm-suppress DocblockTypeContradiction */
        if (!is_numeric($max)) {
            throw new InvalidArgumentException('Аргумент $max должен быть числом');
        }
        $this->max = $max;
    }

    /**
     * @inheritDoc
     */
    public function validate(IValue $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }
        if (!is_numeric($value->getValue())) {
            return false;
        }
        $success = $value->getValue() < $this->max;

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}должно быть максимум {{max}}', 'max');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), ['max' => $this->max]);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'max';
    }
}
