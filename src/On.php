<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Объявление сценариев для правил
 */
class On implements OnInterface
{
    /**
     * @var ChainInterface|null
     */
    private $chain;

    /**
     * @var string[]
     */
    private $scenario;

    /**
     * @var string
     */
    private $fieldName;

    public function __construct(string $fieldName, ?ChainInterface $chain, string ...$scenario)
    {
        $this->fieldName = $fieldName;
        $this->chain = $chain;
        $this->scenario = $scenario;
    }

    /**
     * @inheritDoc
     */
    public function getChain(): ?ChainInterface
    {
        return $this->chain;
    }

    /**
     * @inheritDoc
     */
    public function getScenario(): array
    {
        return $this->scenario;
    }

    /**
     * @inheritDoc
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }
}
