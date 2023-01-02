<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;

/**
 * Проверка на минимальную длину строки
 */
class MinLengthRule extends AbstractRule
{
    /**
     * @var int
     */
    private $min;

    /**
     * Конструктор
     */
    public function __construct(int $min, ?WhenPresenceInterface $presence = null)
    {
        $this->min = $min;
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

        $success = mb_strlen((string) $value->getValue()) >= $this->min;

        if (!$success) {
            $this->addMessage(
                'Длина значения {{if(name)}}"{{name}}" {{endif}}должна быть больше или равно {{min}}',
                'minLength'
            );
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
        return 'minLength';
    }
}
