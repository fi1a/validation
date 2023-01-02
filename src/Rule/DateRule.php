<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;

/**
 * Проверка на формат даты
 */
class DateRule extends AbstractRule
{
    /**
     * @var string
     */
    protected static $defaultFormat = 'd.m.Y';

    /**
     * @var string
     */
    private $format;

    /**
     * Конструктор
     */
    public function __construct(?string $format = null, ?WhenPresenceInterface $presence = null)
    {
        if (!$format) {
            $format = static::$defaultFormat;
        }
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

        $success = date_create_from_format($this->format, (string) $value->getValue()) !== false;

        if (!$success) {
            $this->addMessage(
                '{{if(name)}}"{{name}}" не{{else}}Не{{endif}} является допустимым форматом "{{format}}" даты',
                'date'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), ['format' => $this->format]);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'date';
    }
}
