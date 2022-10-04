<?php

declare(strict_types=1);

namespace Fi1a\Validation;

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
}
