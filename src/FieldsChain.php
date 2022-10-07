<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Цепочка объявления правил
 */
class FieldsChain implements IFieldsChain
{
    /**
     * @var IChain|null
     */
    private $chain;

    /**
     * @var string|null
     */
    private $scenario;

    /**
     * @inheritDoc
     */
    public function on(?string $scenario): IFieldsChain
    {
        $this->scenario = $scenario;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getScenario(): ?string
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
    public function getChain(): ?IChain
    {
        return $this->chain;
    }
}
