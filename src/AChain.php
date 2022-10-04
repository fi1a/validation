<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Rule\IRule;
use InvalidArgumentException;

/**
 * Абстрактный класс цепочки правил
 */
abstract class AChain implements IChain
{
    /**
     * Логика успешной валидации
     *
     * @param bool|IResult $success
     * @param string[] $messages
     */
    abstract protected function setSuccess(
        IResult $result,
        $success,
        ?string $ruleName = null,
        ?string $fieldName = null,
        array $messages = []
    ): void;

    /**
     * @var IRule[]|IChain[]
     */
    private $rules = [];

    /**
     * @var string[]
     */
    private $messages = [];

    /**
     * @inheritDoc
     */
    public function __construct(array $rules = [], array $messages = [])
    {
        $this->setRules($rules);
        $this->setMessages($messages);
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @inheritDoc
     */
    public function setRules(array $rules): bool
    {
        foreach ($rules as $rule) {
            if (!($rule instanceof IRule) && !($rule instanceof IChain)) {
                throw new InvalidArgumentException('The rule must implement the interface ' . IRule::class);
            }
        }

        $this->rules = $rules;

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
    public function setMessages(array $messages): bool
    {
        $this->messages = [];
        foreach ($messages as $fieldName => $message) {
            $this->messages[$fieldName] = $message;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function validate($values, ?string $fieldName = null): IResult
    {
        $result = new Result();
        $result->setSuccess(true);

        foreach ($this->getRules() as $key => $rule) {
            if (is_null($fieldName)) {
                $fieldName = (string) $key;
            }
            if ($rule instanceof IRule) {
                if (is_array($values)) {
                    /**
                     * @var mixed $value
                     */
                    $value = $values[$fieldName] ?? null;
                } else {
                    /**
                     * @var mixed $value
                     */
                    $value = $values;
                }
                $success = $rule->validate($value);
                $this->setSuccess(
                    $result,
                    $success,
                    $rule->getRuleName(),
                    $fieldName,
                    $rule->getMessages()
                );

                continue;
            }
            $this->setSuccess(
                $result,
                $rule->validate($values, $fieldName)
            );
        }

        return $result;
    }
}
