<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;

/**
 * Проверка на максимальное количество элементов в массиве
 */
class MaxCountRule extends AbstractRule
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
        if (!$this->getPresence()->isPresence($value, $this->values)) {
            return true;
        }

        /** @psalm-suppress MixedArgument */
        $success = is_array($value->getValue()) && count($value->getValue()) <= $this->max;

        if (!$success) {
            $this->addMessage(
                'Количество {{if(name)}}"{{name}}" {{endif}}должно быть меньше или равно {{max}}',
                'maxCount'
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
        return 'maxCount';
    }
}
