<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;

/**
 * Проверка на минимальное значение даты
 */
class MinDateRule extends AbstractDateRule
{
    /**
     * @var string
     */
    private $minDate;

    /**
     * @var string
     */
    private $format;

    /**
     * Конструктор
     */
    public function __construct(string $minDate, ?string $format = null, ?WhenPresenceInterface $presence = null)
    {
        if (!$format) {
            $format = static::$defaultFormat;
        }
        $this->minDate = $minDate;
        $this->format = $format;
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

        $min = date_create_from_format($this->format, $this->minDate);
        $value = date_create_from_format($this->format, (string) $value->getValue());

        $success = $min !== false && $value !== false;
        $success = $success && $value >= $min;

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}должно быть минимум {{minDate}}', 'minDate');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), ['minDate' => $this->minDate]);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'minDate';
    }
}
