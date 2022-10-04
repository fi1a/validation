<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Collection\Collection;

/**
 * Результат валидации
 */
class Result implements IResult
{
    /**
     * @var bool
     */
    private $success = false;

    /**
     * @var Collection
     */
    private $errors;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->errors = new Collection(IError::class);
    }

    /**
     * @inheritDoc
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @inheritDoc
     */
    public function setSuccess(bool $success): bool
    {
        $this->success = $success;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function addError(IError $error): bool
    {
        $this->errors->add($error);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function addErrors(array $errors): bool
    {
        $this->errors->exchangeArray(array_merge($this->errors->getArrayCopy(), $errors));

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        /**
         * @var IError[] $errors
         */
        $errors = $this->errors->getArrayCopy();

        return $errors;
    }
}
