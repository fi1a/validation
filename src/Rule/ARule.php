<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

/**
 * Абстрактный класс правила валидации
 */
abstract class ARule implements IRule
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
    protected function addMessage(string $message): bool
    {
        $this->messages[] = $message;

        return true;
    }
}
