<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;

/**
 * Проверка на максимальную длину строки
 */
class MaxLengthRule extends AbstractRule
{
    /**
     * @var int
     */
    private $max;

    /**
     * Конструктор
     */
    public function __construct(int $max, ?WhenPresenceInterface $presence = null)
    {
        $this->max = $max;
        parent::__construct($presence);
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->presence->isPresence($value, $this->values)) {
            return true;
        }

        $success = mb_strlen((string) $value->getValue()) <= $this->max;

        if (!$success) {
            $this->addMessage(
                'Длина значения {{if(name)}}"{{name}}" {{endif}}должна быть меньше или равно {{max}}',
                'maxLength'
            );
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
        return 'maxLength';
    }
}
