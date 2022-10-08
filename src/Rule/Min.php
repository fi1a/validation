<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\IValue;
use InvalidArgumentException;

/**
 * Проверка на минимальное значение
 */
class Min extends ARule
{
    /**
     * @var int|float
     */
    private $min;

    /**
     * Конструктор
     *
     * @param float|int $min
     */
    public function __construct($min)
    {
        /** @psalm-suppress DocblockTypeContradiction */
        if (!is_numeric($min)) {
            throw new InvalidArgumentException('Аргумент $min должен быть числом');
        }
        $this->min = $min;
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
        $success = $value->getValue() > $this->min;

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}должно быть минимум {{min}}', 'min');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), ['min' => $this->min]);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'min';
    }
}
