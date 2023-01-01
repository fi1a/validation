<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Цепочка объявления правил
 */
class FieldsChain implements FieldsChainInterface
{
    /**
     * @var ChainInterface|null
     */
    private $chain;

    /**
     * @var string[]
     */
    private $scenario = [];

    /**
     * @inheritDoc
     */
    public function on($scenario): FieldsChainInterface
    {
        if (!is_array($scenario)) {
            $scenario = [$scenario];
        }
        $this->scenario = $scenario;

        return $this;
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
    public function allOf(): AllOf
    {
        /**
         * @var AllOf $chain
         */
        $chain = AllOf::create();
        $this->chain = $chain;

        return $chain;
    }

    /**
     * @inheritDoc
     */
    public function oneOf(): OneOf
    {
        /**
         * @var OneOf $chain
         */
        $chain = OneOf::create();
        $this->chain = $chain;

        return $chain;
    }

    /**
     * @inheritDoc
     */
    public function getChain(): ?ChainInterface
    {
        return $this->chain;
    }
}
