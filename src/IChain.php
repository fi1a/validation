<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;

/**
 * Цепочка правил валидатора
 *
 * @method IChain required()
 * @method IChain isNull()
 */
interface IChain
{
    public const PATH_SEPARATOR = ':';

    /**
     * Конструктор
     *
     * @param IRule|IChain $rules
     */
    public function __construct(...$rules);

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
     *
     * @return static
     */
    public function setRules(array $rules): IChain;

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
     *
     * @return static
     */
    public function setMessages(array $messages): IChain;

    /**
     * Все правила должны удовлетворять условию
     */
    public function allOf(): AllOf;

    /**
     * Одно из правил должно удовлетворять условию
     */
    public function oneOf(): OneOf;
}
