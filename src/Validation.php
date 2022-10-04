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
     * @var mixed[]
     */
    private $values;

    /**
     * @var
     */
    private $chain;

    /**
     * @var string[]
     */
    private $messages;

    /**
     * @inheritDoc
     */
    public function __construct(IValidator $validator, array $values, IChain $chain, array $messages)
    {
        $this->validator = $validator;
        $this->values = $values;
        $this->chain = $chain;
        $this->messages = $messages;
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
    public function validate(): bool
    {
        return $this->chain->validate($this->values);
    }
}
