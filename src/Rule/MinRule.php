<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;
use InvalidArgumentException;

/**
 * Проверка на минимальное значение
 */
class MinRule extends AbstractRule
{
    /**
     * @var int|float
     */
    private $min;

    /**
     * Конструктор
     *
     * @param float|int $min
     */
    public function __construct($min, ?WhenPresenceInterface $presence = null)
    {
        /** @psalm-suppress DocblockTypeContradiction */
        if (!is_numeric($min)) {
            throw new InvalidArgumentException('Аргумент $min должен быть числом');
        }
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

        $success = is_numeric($value->getValue());
        $success = $success && $value->getValue() >= $this->min;

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}должно быть минимум {{min}}', 'min');
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
        return 'min';
    }
}
