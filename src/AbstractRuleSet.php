<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Абстрактный класс набора правил
 */
abstract class AbstractRuleSet implements RuleSetInterface
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
     * @var ValuesInterface
     */
    protected $values;

    /**
     * @var string|null
     */
    protected $scenario;

    /**
     * @var FieldsChainInterface[][]
     */
    protected $fieldChains = [];

    /**
     * @inheritDoc
     */
    public function __construct($values)
    {
        $this->setValues($values);
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
                $rules[] = new On((string) $fieldName, $fieldsChain->getChain(), ...$fieldsChain->getScenario());
            }
        }

        return $rules;
    }

    /**
     * @inheritDoc
     */
    public function setValues($values): RuleSetInterface
    {
        $this->values = new Values($values);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getValues(): ValuesInterface
    {
        return $this->values;
    }

    /**
     * @inheritDoc
     */
    public function setScenario(?string $scenario): RuleSetInterface
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
     * Возвращает цепочку для объявления правил
     */
    public function fields(string ...$fields): FieldsChainInterface
    {
        $fieldsChain = new FieldsChain();
        foreach ($fields as $field) {
            $this->fieldChains[$field][] = $fieldsChain;
        }

        return $fieldsChain;
    }

    /**
     * Возвращает значение поля
     *
     * @return ValueInterface|ValueInterface[]
     */
    protected function getValue(string $fieldName)
    {
        return $this->values->getValue($fieldName);
    }
}
