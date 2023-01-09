<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;

/**
 * Проверка на равенство даты
 */
class EqualDateRule extends AbstractDateRule
{
    /**
     * @var string
     */
    private $equalDate;

    /**
     * @var string
     */
    private $format;

    /**
     * Конструктор
     */
    public function __construct(string $equalDate, ?string $format = null, ?WhenPresenceInterface $presence = null)
    {
        if (!$format) {
            $format = static::$defaultFormat;
        }
        $this->equalDate = $equalDate;
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

        $equalDate = date_create_from_format($this->format, $this->equalDate);
        $value = date_create_from_format($this->format, (string) $value->getValue());

        $success = $equalDate !== false && $value !== false;
        $success = $success && $value->getTimestamp() === $equalDate->getTimestamp();

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}не равно {{equalDate}}', 'equalDate');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), ['equalDate' => $this->equalDate]);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'equalDate';
    }
}
