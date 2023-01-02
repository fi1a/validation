<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;

/**
 * Проверка на минимальное и максимальное значение даты
 */
class BetweenDateRule extends AbstractDateRule
{
    /**
     * @var string
     */
    private $minDate;

    /**
     * @var string
     */
    private $maxDate;

    /**
     * @var string
     */
    private $format;

    /**
     * Конструктор
     */
    public function __construct(
        string $minDate,
        string $maxDate,
        ?string $format = null,
        ?WhenPresenceInterface $presence = null
    ) {
        if (!$format) {
            $format = static::$defaultFormat;
        }
        $this->minDate = $minDate;
        $this->maxDate = $maxDate;
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

        $minDate = date_create_from_format($this->format, $this->minDate);
        $maxDate = date_create_from_format($this->format, $this->maxDate);
        $value = date_create_from_format($this->format, (string) $value->getValue());

        $success = $minDate !== false && $maxDate !== false && $value !== false;
        $success = $success
            && $value->getTimestamp() >= $minDate->getTimestamp()
            && $value->getTimestamp() <= $maxDate->getTimestamp();

        if (!$success) {
            $this->addMessage(
                'Значение {{if(name)}}"{{name}}" {{endif}}должно быть '
                . 'больше или равно {{minDate}} и меньше или равно {{maxDate}}',
                'betweenDate'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(
            parent::getVariables(),
            [
                'minDate' => $this->minDate,
                'maxDate' => $this->maxDate,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'betweenDate';
    }
}
