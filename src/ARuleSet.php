<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Абстрактный класс набора правил
 */
abstract class ARuleSet implements IRuleSet
{
    /**
     * @var string[]
     */
    protected $messages = [];

    /**
     * @var string[]
     */
    protected $titles = [];

    /**
     * @var mixed
     */
    protected $values;

    /**
     * @var string|null
     */
    protected $scenario;

    /**
     * @var IFieldsChain[][]
     */
    protected $fieldChains = [];

    /**
     * @inheritDoc
     */
    public function __construct($values, ?string $scenario = null)
    {
        $this->setValues($values)
            ->setScenario($scenario);
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
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        $rules = [];

        foreach ($this->fieldChains as $fieldName => $fieldsChains) {
            foreach ($fieldsChains as $fieldsChain) {
                $chain = $fieldsChain->getChain();
                if (
                    (is_null($fieldsChain->getScenario()) || $fieldsChain->getScenario() === $this->scenario)
                    && !is_null($chain)
                ) {
                    if (!isset($rules[$fieldName])) {
                        $rules[$fieldName] = AllOf::create();
                    }
                    $rules[$fieldName]->addRule($chain);
                }
            }
        }

        return $rules;
    }

    /**
     * @inheritDoc
     */
    public function setValues($values): IRuleSet
    {
        $this->values = $values;

        return $this;
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
    public function setScenario(?string $scenario): IRuleSet
    {
        $this->scenario = $scenario;

        return $this;
    }

    /**
     * Возвращает цепочку для объявления правил
     */
    public function fields(string ...$fields): IFieldsChain
    {
        $fieldsChain = new FieldsChain();
        foreach ($fields as $field) {
            $this->fieldChains[$field][] = $fieldsChain;
        }

        return $fieldsChain;
    }
}
