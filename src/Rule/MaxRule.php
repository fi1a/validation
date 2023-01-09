<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;

/**
 * Проверка на максимальное значение
 */
class MaxRule extends AbstractRule
{
    /**
     * @var float
     */
    private $max;

    /**
     * Конструктор
     *
     * @param float|int $max
     */
    public function __construct(float $max, ?WhenPresenceInterface $presence = null)
    {
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
        $success = $success && (float) $value->getValue() <= $this->max;

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
