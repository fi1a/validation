<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Format\Formatter;
use Fi1a\Validation\Rule\RuleInterface;
use InvalidArgumentException;

/**
 * Абстрактный класс цепочки правил
 */
abstract class AbstractChain implements ChainInterface
{
    /**
     * Логика успешной валидации
     *
     * @param bool|ResultInterface $success
     * @param string[]             $messages
     */
    abstract protected function setSuccess(
        ResultInterface $result,
        $success,
        ?string $ruleName = null,
        ?string $fieldName = null,
        array $messages = []
    ): void;

    /**
     * Подготовка результата
     */
    protected function prepareResult(ResultInterface $result): ResultInterface
    {
        return $result;
    }

    /**
     * @var RuleInterface[]|ChainInterface[]
     */
    private $rules = [];

    /**
     * @var string[]
     */
    private $messages = [];

    /**
     * @var string[]
     */
    private $titles = [];

    /**
     * @var bool
     */
    private $internalAsArray = true;

    /**
     * Конструктор
     *
     * @param RuleInterface|ChainInterface ...$rules
     */
    protected function __construct(...$rules)
    {
        $this->setRules($rules);
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
    public function setRules(array $rules): ChainInterface
    {
        foreach ($rules as $rule) {
            if (!($rule instanceof RuleInterface) && !($rule instanceof ChainInterface)) {
                throw new InvalidArgumentException('The rule must implement the interface ' . RuleInterface::class);
            }
            if ($rule instanceof ChainInterface) {
                $rule->setMessages($this->getMessages());
            }
        }

        $this->rules = $rules;

        return $this;
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
    public function setMessages(array $messages): ChainInterface
    {
        $this->messages = [];
        foreach ($messages as $fieldName => $message) {
            $this->messages[$fieldName] = $message;
        }
        foreach ($this->rules as $chain) {
            if (!($chain instanceof ChainInterface)) {
                continue;
            }
            $chain->setMessages($messages);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTitles(array $titles): ChainInterface
    {
        $this->titles = [];
        foreach ($titles as $fieldName => $title) {
            $this->titles[$fieldName] = $title;
        }
        foreach ($this->rules as $chain) {
            if (!($chain instanceof ChainInterface)) {
                continue;
            }
            $chain->setTitles($titles);
        }

        return $this;
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
    public function validate($values, $fieldName = null): ResultInterface
    {
        if (!($values instanceof ValuesInterface)) {
            $values = new Values($values);
        }
        $result = new Result();
        if (is_null($fieldName)) {
            $values->setAsArray(false);
        }

        foreach ($this->getRules() as $key => $rule) {
            $internalFieldName = is_null($fieldName) || $fieldName === false ? (string) $key : (string) $fieldName;
            if ($rule instanceof RuleInterface) {
                $rule->setValues($values);
                $rule->setTitles($this->getTitles());
                $value = $values->getValue($internalFieldName);
                if (is_array($values->getValues()) && $values->asArray() && is_array($value)) {
                    foreach ($value as $item) {
                        $success = $rule->validate($item);
                        $messages = $this->formatMessages($rule, $item, $values);
                        $this->setSuccess(
                            $result,
                            $success,
                            $rule::getRuleName(),
                            (string) $item->getPath(),
                            $messages
                        );
                    }

                    continue;
                }

                assert($value instanceof ValueInterface);
                $success = $rule->validate($value);
                $messages = $this->formatMessages($rule, $value, $values);
                $this->setSuccess(
                    $result,
                    $success,
                    $rule::getRuleName(),
                    $internalFieldName,
                    $messages
                );

                continue;
            }

            $this->setSuccess(
                $result,
                $rule->validate($values, $internalFieldName)
            );
        }

        return $this->prepareResult($result);
    }

    /**
     * Форматирование сообщений
     *
     * @return string[]
     */
    private function formatMessages(RuleInterface $rule, ValueInterface $value, ValuesInterface $values): array
    {
        $userMessages = $this->getMessages();
        $messages = $rule->getMessages();
        $titles = $this->getTitles();
        $fieldTitle = (string) $value->getPath();
        if (!$value->isWildcardItem()) {
            $fieldTitle = (string) $value->getWildcardPath();
        }
        if (array_key_exists((string) $value->getWildcardPath(), $titles)) {
            $fieldTitle = $titles[(string) $value->getWildcardPath()];
        }
        if (!$values->asArray()) {
            $fieldTitle = '';
        }
        $variables = array_merge([
            'name' => $fieldTitle,
            'value' => $value->getValue(),
        ], $rule->getVariables());
        foreach ($messages as $key => $message) {
            if (array_key_exists((string) $value->getWildcardPath() . '|' . $key, $userMessages)) {
                $messages[$key] = Formatter::format(
                    $userMessages[(string) $value->getWildcardPath() . '|' . $key],
                    $variables
                );

                continue;
            }
            if (array_key_exists($key, $userMessages)) {
                $messages[$key] = Formatter::format($userMessages[$key], $variables);

                continue;
            }

            $messages[$key] = Formatter::format($message, $variables);
        }

        return $messages;
    }

    /**
     * @inheritDoc
     */
    public function __call(string $name, array $arguments)
    {
        $class = Validator::getRuleClassByName($name);
        /**
         * @psalm-suppress InvalidStringClass
         * @psalm-suppress UndefinedClass
         * @var RuleInterface $rule
         */
        $rule = new $class(...$arguments);
        $this->rules[] = $rule;

        return $this;
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
        $this->rules[] = $chain;
        $chain->setMessages($this->getMessages());

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
        $this->rules[] = $chain;
        $chain->setMessages($this->getMessages());

        return $chain;
    }

    /**
     * @inheritDoc
     */
    public function addRule($rule): ChainInterface
    {
        if (!($rule instanceof RuleInterface) && !($rule instanceof ChainInterface)) {
            throw new InvalidArgumentException('The rule must implement the interface ' . RuleInterface::class);
        }

        $rules = $this->getRules();
        $rules[] = $rule;
        $this->setRules($rules);

        return $this;
    }
}
