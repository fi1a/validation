<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;
use InvalidArgumentException;

/**
 * Проверка на максимальное и мимальное значение
 */
class BetweenRule extends AbstractRule
{
    /**
     * @var int|float
     */
    private $min;

    /**
     * @var int|float
     */
    private $max;

    /**
     * Конструктор
     *
     * @param float|int $min
     * @param float|int $max
     */
    public function __construct($min, $max, ?WhenPresenceInterface $presence = null)
    {
        /** @psalm-suppress DocblockTypeContradiction */
        if (!is_numeric($min)) {
            throw new InvalidArgumentException('Аргумент $min должен быть числом');
        }
        /** @psalm-suppress DocblockTypeContradiction */
        if (!is_numeric($max)) {
            throw new InvalidArgumentException('Аргумент $max должен быть числом');
        }
        if ($min >= $max) {
            throw new InvalidArgumentException('Аргумент $max должен быть больше $min');
        }
        $this->min = $min;
        $this->max = $max;
        parent::__construct($presence);
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->getPresence()->isPresence($value, $this->values)) {
            return true;
        }
        $success = is_numeric($value->getValue());
        $success = $success && $this->max >= $value->getValue() && $this->min <= $value->getValue();

        if (!$success) {
            $this->addMessage(
                'Значение {{if(name)}}"{{name}}" {{endif}}должно быть '
                . 'больше или равно {{min}} и меньше или равно {{max}}',
                'between'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), [
            'min' => $this->min,
            'max' => $this->max,
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'between';
    }
}
