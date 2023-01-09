<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Presence\WhenPresenceInterface;

/**
 * Класс проверки значений
 */
interface ValidationInterface
{
    /**
     * Конструктор
     *
     * @param mixed $values
     * @param string[] $messages
     * @param string[] $titles
     */
    public function __construct(
        ValidatorInterface $validator,
        $values,
        ChainInterface $chain,
        array $messages,
        array $titles
    );

    /**
     * Возвращает экземпляр класса валидатора
     */
    public function getValidator(): ValidatorInterface;

    /**
     * Метод валидации
     */
    public function validate(): ResultInterface;

    /**
     * Устанавливает сообщения об ошибках
     *
     * @param string[] $messages
     */
    public function setMessages(array $messages): bool;

    /**
     * Устанавливает сообщение об ошибке
     */
    public function setMessage(string $key, ?string $message): bool;

    /**
     * Возвращает сообщения об ошибках
     *
     * @return string[]
     */
    public function getMessages(): array;

    /**
     * Возвращает значения
     *
     * @return mixed
     */
    public function getValues();

    /**
     * Устанавливает значения
     *
     * @param mixed $values
     */
    public function setValues($values): bool;

    /**
     * Установить заголовок поля
     */
    public function setTitle(string $fieldName, ?string $title): bool;

    /**
     * Установить заголовки полей
     *
     * @param string[]|null[] $titles
     */
    public function setTitles(array $titles): bool;

    /**
     * Возвращает заголовки полей
     *
     * @return string[]|null[]
     */
    public function getTitles(): array;

    /**
     * Установить значение объекта определяющего присутсвие
     */
    public function setPresence(?WhenPresenceInterface $presence): bool;

    /**
     * Вернуть значение объекта определяющего присутсвие
     */
    public function getPresence(): ?WhenPresenceInterface;
}
