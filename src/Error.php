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
     * @var string|null
     */
    private $messageKey;

    /**
     * @inheritDoc
     */
    public function __construct(
        string $ruleName,
        ?string $fieldName = null,
        ?string $messageKey = null,
        ?string $message = null
    ) {
        $this->ruleName = $ruleName;
        $this->fieldName = $fieldName;
        $this->message = $message;
        $this->messageKey = $messageKey;
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

    /**
     * @inheritDoc
     */
    public function getMessageKey(): ?string
    {
        return $this->messageKey;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return (string) $this->getMessage();
    }
}
