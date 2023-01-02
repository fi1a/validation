<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\Rule\RuleInterface;

/**
 * Цепочка правил валидатора
 *
 * @method ChainInterface required()
 * @method ChainInterface null(?WhenPresenceInterface $presence = null)
 * @method ChainInterface numeric(?WhenPresenceInterface $presence = null)
 * @method ChainInterface alpha(?WhenPresenceInterface $presence = null)
 * @method ChainInterface alphaNumeric(?WhenPresenceInterface $presence = null)
 * @method ChainInterface email(?WhenPresenceInterface $presence = null)
 * @method ChainInterface min(float $min, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface max(float $max, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface between(float $min, float $max, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface array(?WhenPresenceInterface $presence = null)
 * @method ChainInterface boolean(?WhenPresenceInterface $presence = null)
 * @method ChainInterface integer(?WhenPresenceInterface $presence = null)
 * @method ChainInterface json(?WhenPresenceInterface $presence = null)
 * @method ChainInterface minLength(int $min, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface maxLength(int $max)
 * @method ChainInterface betweenLength(int $min, int $max, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface minCount(int $min, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface maxCount(int $max, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface betweenCount(int $min, int $max, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface in($presence = null, ...$in)
 * @method ChainInterface notIn($presence = null, ...$notIn)
 * @method ChainInterface same(string $fieldName, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface date(string $format = 'd.m.Y', ?WhenPresenceInterface $presence = null)
 * @method ChainInterface regex(string $regex, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface fileSize(string $min, string $max, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface mime($presence = null, string ...$extensions)
 * @method ChainInterface strictIn($presence = null, ...$in)
 * @method ChainInterface strictNotIn($presence = null, ...$notIn)
 * @method ChainInterface requiredIfPresence(?WhenPresenceInterface $presence = null)
 * @method ChainInterface url(?WhenPresenceInterface $presence = null)
 * @method ChainInterface equal(float $equal, ?WhenPresenceInterface $presence = null)
 * @method ChainInterface minDate(string $minDate, ?string $format = null, ?WhenPresenceInterface $presence = null)
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
    public function setPresence(?WhenPresenceInterface $presence): bool;

    /**
     * Вернуть значение объекта определяющего присутсвие
     */
    public function getPresence(): ?WhenPresenceInterface;
}
