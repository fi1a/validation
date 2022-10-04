<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;

/**
 * Цепочка правил валидатора
 */
interface IChain
{
    /**
     * Конструктор
     *
     * @param IRule[]|IChain[] $rules
     * @param string[] $messages
     */
    public function __construct(array $rules = [], array $messages = []);

    /**
     * Метод валидации
     *
     * @param mixed $values
     */
    public function validate($values, ?string $fieldName = null): IResult;

    /**
     * Возвращает правила
     *
     * @return IRule[]|IChain[]
     */
    public function getRules(): array;

    /**
     * Устанавливает правила
     *
     * @param IRule[]|IChain[] $rules
     */
    public function setRules(array $rules): bool;

    /**
     * Возвращает сообщения об ошибках
     *
     * @return string[]
     */
    public function getMessages(): array;

    /**
     * Устанавливает сообщения об ошибках
     *
     * @param string[] $messages
     */
    public function setMessages(array $messages): bool;
}
