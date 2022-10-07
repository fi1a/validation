<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Класс проверки значений
 */
interface IValidation
{
    /**
     * Конструктор
     *
     * @param mixed[] $values
     * @param string[] $messages
     * @param string[] $titles
     */
    public function __construct(
        IValidator $validator,
        array $values,
        IChain $chain,
        array $messages,
        array $titles
    );

    /**
     * Возвращает экземпляр класса валидатора
     */
    public function getValidator(): IValidator;

    /**
     * Метод валидации
     */
    public function validate(): IResult;

    /**
     * Устанавливает сообщения об ошибках
     *
     * @param string[] $messages
     */
    public function setMessages(array $messages): bool;

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
    public function setTitle(string $fieldName, string $title): bool;

    /**
     * Установить заголовки полей
     *
     * @param string[] $titles
     */
    public function setTitles(array $titles): bool;

    /**
     * Возвращает заголовки полей
     *
     * @return string[]
     */
    public function getTitles(): array;
}
