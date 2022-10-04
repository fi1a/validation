<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Ошибка
 */
class Error implements IError
{
    /**
     * @var string
     */
    private $ruleName;

    /**
     * @var string|null
     */
    private $fieldName;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @inheritDoc
     */
    public function __construct(string $ruleName, ?string $fieldName = null, ?string $message = null)
    {
        $this->ruleName = $ruleName;
        $this->fieldName = $fieldName;
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function getRuleName(): string
    {
        return $this->ruleName;
    }

    /**
     * @inheritDoc
     */
    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
}
