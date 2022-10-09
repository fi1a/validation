<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

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
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return [];
    }
}
