<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Ошибка
 */
interface ErrorInterface
{
    /**
     * Конструктор
     */
    public function __construct(
        string $ruleName,
        ?string $fieldName = null,
        ?string $messageKey = null,
        ?string $message = null
    );

    /**
     * Возвращает название правила
     */
    public function getRuleName(): string;

    /**
     * Возвращает название поля
     */
    public function getFieldName(): ?string;

    /**
     * Возвращает сообщение
     */
    public function getMessage(): ?string;

    /**
     * Возвращает ключ сообщения
     */
    public function getMessageKey(): ?string;
}
