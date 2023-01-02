<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;

/**
 * Проверка на равенство
 */
class EqualRule extends AbstractRule
{
    /**
     * @var float
     */
    private $equal;

    /**
     * Конструктор
     *
     * @param float|int $min
     */
    public function __construct(float $equal, ?WhenPresenceInterface $presence = null)
    {
        $this->equal = $equal;
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
        $success = $success && (float) $value->getValue() === $this->equal;

        if (!$success) {
            $this->addMessage('Значение {{if(name)}}"{{name}}" {{endif}}должно быть равно {{equal}}', 'equal');
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), ['equal' => $this->equal]);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'equal';
    }
}
