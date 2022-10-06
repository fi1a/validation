<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Класс проверки значений
 */
class Validation implements IValidation
{
    /**
     * @var IValidator
     */
    private $validator;

    /**
     * @var mixed
     */
    private $values;

    /**
     * @var IChain
     */
    private $chain;

    /**
     * @var string[]
     */
    private $messages = [];

    /**
     * @inheritDoc
     */
    public function __construct(IValidator $validator, array $values, IChain $chain, array $messages)
    {
        $this->validator = $validator;
        $this->setValues($values);
        $this->chain = $chain;
        $this->setMessages($messages);
    }

    /**
     * @inheritDoc
     */
    public function getValidator(): IValidator
    {
        return $this->validator;
    }

    /**
     * @inheritDoc
     */
    public function validate(): IResult
    {
        return $this->chain->validate($this->values);
    }

    /**
     * @inheritDoc
     */
    public function setMessages(array $messages): bool
    {
        $this->messages = $messages;
        $this->chain->setMessages($messages);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @inheritDoc
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @inheritDoc
     */
    public function setValues($values): bool
    {
        $this->values = $values;

        return true;
    }
}
