<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;
use InvalidArgumentException;

/**
 * Проверка на минимальное и максимальное количество элементов в массиве
 */
class BetweenCountRule extends AbstractRule
{
    /**
     * @var int
     */
    private $min;

    /**
     * @var int
     */
    private $max;

    /**
     * Конструктор
     */
    public function __construct(int $min, int $max)
    {
        if ($min >= $max) {
            throw new InvalidArgumentException('Аргумент $max должен быть больше $min');
        }
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }

        /** @psalm-suppress MixedArgument */
        $success = is_array($value->getValue())
            && $this->max >= count($value->getValue())
            && $this->min <= count($value->getValue());

        if (!$success) {
            $this->addMessage(
                'Количество {{if(name)}}"{{name}}" {{endif}}должно быть '
                . 'больше или равно {{min}} и меньше или равно {{max}}',
                'betweenCount'
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
        return 'betweenCount';
    }
}
