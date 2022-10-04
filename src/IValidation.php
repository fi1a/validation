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
     */
    public function __construct(IValidator $validator, array $values, IChain $chain, array $messages);

    /**
     * Возвращает экземпляр класса валидатора
     */
    public function getValidator(): IValidator;

    /**
     * Метод валидации
     */
    public function validate(): bool;
}
