<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Presence\WhenPresenceInterface as WP;
use Fi1a\Validation\Rule\RuleInterface;

/**
 * Цепочка правил валидатора
 *
 * @method ChainInterface required()
 * @method ChainInterface null(?WP $presence = null)
 * @method ChainInterface numeric(?WP $presence = null)
 * @method ChainInterface alpha(?WP $presence = null)
 * @method ChainInterface alphaNumeric(?WP $presence = null)
 * @method ChainInterface email(?WP $presence = null)
 * @method ChainInterface min(float $min, ?WP $presence = null)
 * @method ChainInterface max(float $max, ?WP $presence = null)
 * @method ChainInterface between(float $min, float $max, ?WP $presence = null)
 * @method ChainInterface array(?WP $presence = null)
 * @method ChainInterface boolean(?WP $presence = null)
 * @method ChainInterface integer(?WP $presence = null)
 * @method ChainInterface json(?WP $presence = null)
 * @method ChainInterface minLength(int $min, ?WP $presence = null)
 * @method ChainInterface maxLength(int $max)
 * @method ChainInterface betweenLength(int $min, int $max, ?WP $presence = null)
 * @method ChainInterface minCount(int $min, ?WP $presence = null)
 * @method ChainInterface maxCount(int $max, ?WP $presence = null)
 * @method ChainInterface betweenCount(int $min, int $max, ?WP $presence = null)
 * @method ChainInterface in($presence = null, ...$in)
 * @method ChainInterface notIn($presence = null, ...$notIn)
 * @method ChainInterface same(string $fieldName, ?WP $presence = null)
 * @method ChainInterface date(string $format = 'd.m.Y', ?WP $presence = null)
 * @method ChainInterface regex(string $regex, ?WP $presence = null)
 * @method ChainInterface fileSize(string $min, string $max, ?WP $presence = null)
 * @method ChainInterface mime($presence = null, string ...$extensions)
 * @method ChainInterface strictIn($presence = null, ...$in)
 * @method ChainInterface strictNotIn($presence = null, ...$notIn)
 * @method ChainInterface requiredIfPresence(?WP $presence = null)
 * @method ChainInterface url(?WP $presence = null)
 * @method ChainInterface equal(float $equal, ?WP $presence = null)
 * @method ChainInterface minDate(string $minDate, ?string $format = null, ?WP $presence = null)
 * @method ChainInterface maxDate(string $maxDate, ?string $format = null, ?WP $presence = null)
 * @method ChainInterface betweenDate(string $minDate, string $maxDate, ?string $format = null, ?WP $presence = null)
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

    /**
     * Установить значение объекта определяющего присутсвие
     */
    public function setPresence(?WP $presence): bool;

    /**
     * Вернуть значение объекта определяющего присутсвие
     */
    public function getPresence(): ?WP;
}
