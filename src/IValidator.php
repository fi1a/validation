<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Валидатор
 */
interface IValidator
{
    /**
     * Конструктор
     *
     * @param string[] $messages
     */
    public function __construct(array $messages = []);

    /**
     * Создать класс проверки значений
     *
     * @param mixed[]       $values
     * @param string[]|null $rules
     * @param string[]      $messages
     *
     * @return mixed
     */
    public function make(array $values, ?array $rules = null, array $messages = []): IValidation;
}
