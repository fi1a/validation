<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;
use Fi1a\Validation\ValuesInterface;
use LogicException;

/**
 * Абстрактный класс правила валидации
 */
abstract class AbstractRule implements RuleInterface
{
    /**
     * @var string[]
     */
    protected $messages = [];

    /**
     * @var ValuesInterface|null
     */
    protected $values;

    /**
     * @inheritDoc
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Добавить сообщение об ошибке
     */
    protected function addMessage(string $message, string $key): bool
    {
        $this->messages[$key] = $message;

        return true;
    }

    /**
     * Возвращает значение поля
     *
     * @return ValueInterface|ValueInterface[]
     */
    protected function getValue(string $fieldName)
    {
        if (!$this->values) {
            throw new LogicException('$values not set');
        }

        return $this->values->getValue($fieldName);
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function setValues(ValuesInterface $values): bool
    {
        $this->values = $values;

        return true;
    }
}
