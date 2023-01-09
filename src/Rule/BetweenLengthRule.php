<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
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
    public function __construct(int $min, int $max, ?WhenPresenceInterface $presence = null)
    {
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

        $length = mb_strlen((string) $value->getValue());
        $success = $this->max >= $length && $this->min <= $length;

        if (!$success) {
            $this->addMessage(
                'Длина значения {{if(name)}}"{{name}}" {{endif}}должна быть '
                . 'больше или равно {{min}} и меньше или равно {{max}}',
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
