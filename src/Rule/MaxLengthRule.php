<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;

/**
 * Проверка на максимальную длину строки
 */
class MaxLengthRule extends AbstractRule
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

        $success = mb_strlen((string) $value->getValue()) < $this->max;

        if (!$success) {
            $this->addMessage(
                'Длина значения {{if(name)}}"{{name}}" {{endif}}должна быть меньше {{max}}',
                'maxLength'
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
        return 'maxLength';
    }
}
