<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

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
    public function __construct(int $max)
    {
        $this->max = $max;
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
        $success = is_array($value->getValue()) && count($value->getValue()) < $this->max;

        if (!$success) {
            $this->addMessage(
                'Количество {{if(name)}}"{{name}}" {{endif}}должно быть меньше {{max}}',
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
