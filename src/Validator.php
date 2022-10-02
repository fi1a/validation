<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Валидатор
 */
class Validator implements IValidator
{
    /**
     * @inheritDoc
     */
    public function __construct(array $messages = [])
    {
    }

    /**
     * @inheritDoc
     */
    public function make($values, ?array $rules = null, array $messages = []): IValidation
    {
        return new Validation();
    }
}
