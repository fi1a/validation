<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\RuleInterface;

/**
 * Цепочка правил валидатора
 *
 * @method ChainInterface required()
 * @method ChainInterface null()
 * @method ChainInterface numeric()
 * @method ChainInterface alpha()
 * @method ChainInterface alphaNumeric()
 * @method ChainInterface email()
 * @method ChainInterface min(float $min)
 * @method ChainInterface max(float $max)
 * @method ChainInterface between(float $min, float $max)
 * @method ChainInterface array()
 * @method ChainInterface boolean()
 * @method ChainInterface integer()
 * @method ChainInterface json()
 * @method ChainInterface minLength(int $min)
 * @method ChainInterface maxLength(int $max)
 * @method ChainInterface betweenLength(int $min, int $max)
 * @method ChainInterface minCount(int $min)
 * @method ChainInterface maxCount(int $max)
 * @method ChainInterface betweenCount(int $min, int $max)
 * @method ChainInterface in(...$in)
 * @method ChainInterface notIn(...$notIn)
 * @method ChainInterface same(string $fieldName)
 * @method ChainInterface date(string $format = 'd.m.Y')
 * @method ChainInterface regex(string $regex)
 */
interface ChainInterface
{
    /**
     * Метод валидации
     *
     * @param mixed $values
     * @param bool|string|null $fieldName
     */
    public function validate($values, $fieldName = null): ResultInterface;

    /**
     * Возвращает правила
     *
     * @return RuleInterface[]|ChainInterface[]
     */
    public function getRules(): array;

    /**
     * Устанавливает правила
     *
     * @param RuleInterface[]|ChainInterface[] $rules
     *
     * @return static
     */
    public function setRules(array $rules): ChainInterface;

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
    public function setMessages(array $messages): ChainInterface;

    /**
     * Установить заголовки полей
     *
     * @param string[]|null[] $titles
     */
    public function setTitles(array $titles): ChainInterface;

    /**
     * Возвращает заголовки полей
     *
     * @return string[]|null[]
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
     * @param RuleInterface|ChainInterface ...$rules
     */
    public static function create(...$rules): ChainInterface;

    /**
     * Добавить правило
     *
     * @param RuleInterface|ChainInterface $rule
     */
    public function addRule($rule): ChainInterface;
}
