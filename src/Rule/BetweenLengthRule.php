<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;
use InvalidArgumentException;

/**
 * Проверка на максимальную и минимальную длину строки
 */
class BetweenLengthRule extends AbstractRule
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

        $length = mb_strlen((string) $value->getValue());
        $success = $this->max > $length && $this->min < $length;

        if (!$success) {
            $this->addMessage(
                'Длина значения {{if(name)}}"{{name}}" {{endif}}должна быть больше {{min}} и меньше {{max}}',
                'betweenLength'
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
        return 'betweenLength';
    }
}
