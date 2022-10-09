<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

/**
 * Проверка на минимальное количество элементов в массиве
 */
class MinCountRule extends AbstractRule
{
    /**
     * @var int
     */
    private $min;

    /**
     * Конструктор
     */
    public function __construct(int $min)
    {
        $this->min = $min;
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }

        /** @psalm-suppress MixedArgument */
        $success = is_array($value->getValue()) && count($value->getValue()) > $this->min;

        if (!$success) {
            $this->addMessage(
                'Количество {{if(name)}}"{{name}}" {{endif}}должно быть больше {{min}}',
                'minCount'
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
        return 'minCount';
    }
}
