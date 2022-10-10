<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Результат валидации
 */
class Result implements ResultInterface
{
    /**
     * @var bool|null
     */
    private $success;

    /**
     * @var Errors
     */
    private $errors;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->errors = new Errors(ErrorInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function isSuccess(): ?bool
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
    public function addError(ErrorInterface $error): bool
    {
        $this->errors->add($error);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function addErrors($errors): bool
    {
        if ($errors instanceof Errors) {
            $errors = $errors->getArrayCopy();
        }
        $this->errors->exchangeArray(array_merge($this->errors->getArrayCopy(), $errors));

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): Errors
    {
        return $this->errors;
    }

    /**
     * @inheritDoc
     */
    public function clearErrors(): bool
    {
        $this->errors = new Errors(ErrorInterface::class);

        return true;
    }
}
