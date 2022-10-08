<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;

/**
 * Цепочка правил валидатора
 *
 * @method IChain required()
 * @method IChain isNull()
 * @method IChain numeric()
 * @method IChain alpha()
 * @method IChain alphaNumeric()
 * @method IChain email()
 * @method IChain min(float $min)
 * @method IChain max(float $max)
 * @method IChain between(float $min, float $max)
 * @method IChain isArray()
 */
interface IChain
{
    public const PATH_SEPARATOR = ':';

    /**
     * Метод валидации
     *
     * @param mixed $values
     * @param bool|string|null $fieldName
     */
    public function validate($values, $fieldName = null): IResult;

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
     * Установить заголовки полей
     *
     * @param string[] $titles
     */
    public function setTitles(array $titles): IChain;

    /**
     * Возвращает заголовки полей
     *
     * @return string[]
     */
    public function getTitles(): array;

    /**
     * Все правила должны удовлетворять условию
     */
    public function allOf(): AllOf;

    /**
     * Одно из правил должно удовлетворять условию
     */
    public function oneOf(): OneOf;

    /**
     * Фабричный метод
     *
     * @param IRule|IChain ...$rules
     */
    public static function create(...$rules): IChain;

    /**
     * Добавить правило
     *
     * @param IRule|IChain $rule
     */
    public function addRule($rule): IChain;
}
