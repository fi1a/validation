<?php

declare(strict_types=1);

namespace Fi1a\Validation\AST;

/**
 * Правило
 */
class Rule implements RuleInterface
{
    /**
     * @var string
     */
    private $ruleName;

    /**
     * @var ArgumentInterface[]
     */
    private $arguments;

    /**
     * @inheritDoc
     */
    public function __construct(string $ruleName, array $arguments)
    {
        $this->ruleName = $ruleName;
        $this->arguments = $arguments;
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
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    public function getArgumentsValues(): array
    {
        $values = [];
        foreach ($this->getArguments() as $argument) {
            $values[] = $argument->getValue();
        }

        return $values;
    }
}
