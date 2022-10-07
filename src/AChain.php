<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Format\Formatter;
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
     * @var string[]
     */
    private $titles = [];

    /**
     * Конструктор
     *
     * @param IRule|IChain ...$rules
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
    public function setRules(array $rules): IChain
    {
        foreach ($rules as $rule) {
            if (!($rule instanceof IRule) && !($rule instanceof IChain)) {
                throw new InvalidArgumentException('The rule must implement the interface ' . IRule::class);
            }
            if ($rule instanceof IChain) {
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
    public function setMessages(array $messages): IChain
    {
        $this->messages = [];
        foreach ($messages as $fieldName => $message) {
            $this->messages[$fieldName] = $message;
        }
        foreach ($this->rules as $chain) {
            if (!($chain instanceof IChain)) {
                continue;
            }
            $chain->setMessages($messages);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTitles(array $titles): IChain
    {
        $this->titles = [];
        foreach ($titles as $fieldName => $title) {
            $this->titles[$fieldName] = $title;
        }
        foreach ($this->rules as $chain) {
            if (!($chain instanceof IChain)) {
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
    public function validate($values, ?string $fieldName = null): IResult
    {
        $result = new Result();

        foreach ($this->getRules() as $key => $rule) {
            $internalFieldName = is_null($fieldName) ? (string) $key : $fieldName;

            if ($rule instanceof IRule) {
                if (is_array($values)) {
                    $tree = $this->getValue($this->getKeys($internalFieldName), $values);
                    foreach ($this->flatten($tree) as $value) {
                        $success = $rule->validate($value);
                        $messages = $this->formatMessages(
                            $rule,
                            (string) $value->getPath(),
                            (string) $value->getWildcardPath()
                        );
                        $this->setSuccess(
                            $result,
                            $success,
                            $rule::getRuleName(),
                            (string) $value->getPath(),
                            $messages
                        );
                    }

                    continue;
                }

                $value = new Value();
                $value->setValue($values);
                $value->setPath($internalFieldName);
                $value->setWildcardPath($internalFieldName);
                $value->setPresence(true);

                $success = $rule->validate($value);
                $messages = $this->formatMessages($rule, $internalFieldName, $internalFieldName);
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

        return $result;
    }

    /**
     * Форматирование сообщений
     *
     * @return string[]
     */
    private function formatMessages(IRule $rule, string $fieldName, string $validationPath): array
    {
        $userMessages = $this->getMessages();
        $messages = $rule->getMessages();
        $titles = $this->getTitles();
        $fieldTitle = $fieldName;
        if (array_key_exists($fieldName, $titles)) {
            $fieldTitle = $titles[$fieldName];
        }
        $variables = array_merge([
            'name' => $fieldTitle,
        ], $rule->getVariables());
        foreach ($messages as $key => $message) {
            if (array_key_exists($key . '|' . $validationPath, $userMessages)) {
                $messages[$key] = Formatter::format($userMessages[$key . '|' . $validationPath], $variables);

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
     * @param string[] $paths
     * @param mixed $values
     */
    private function getValue(array $paths, $values, ?string $realPath = null, ?string $validationPath = null): IValue
    {
        $path = array_shift($paths);
        if (is_null($realPath)) {
            $realPath = '';
        }
        if (is_null($validationPath)) {
            $validationPath = '';
        }
        $validationPath .= ($validationPath ? ':' : '') . $path;
        if ($path !== '*') {
            $realPath .= ($realPath ? ':' : '') . $path;
        }
        $return = new Value();
        if ($path === '*') {
            if (!is_array($values)) {
                $return->setPath($realPath);
                $return->setWildcardPath($validationPath);
                $return->setPresence(false);

                return $return;
            }
            $result = [];
            /**
             * @psalm-suppress MixedAssignment
             */
            foreach ($values as $index => $value) {
                $result[] = $this->getValue(
                    $paths,
                    $value,
                    $realPath . ($realPath ? ':' : '') . $index,
                    $validationPath
                );
            }

            $return->setValue($result);
            $return->setWildcard(true);
            $return->setPath($realPath);
            $return->setWildcardPath($validationPath);
            $return->setPresence(true);

            return $return;
        }
        if (!is_array($values) || !array_key_exists($path, $values)) {
            $return->setPath($realPath);
            $return->setWildcardPath($validationPath);
            $return->setPresence(false);

            return $return;
        }
        if (count($paths) > 0) {
            return $this->getValue($paths, $values[$path], $realPath, $validationPath);
        }

        $return->setValue($values[$path]);
        $return->setPath($realPath);
        $return->setWildcardPath($validationPath);
        $return->setPresence(true);

        return $return;
    }

    /**
     * Возвращает массив ключей
     *
     * @param string $path путь
     *
     * @return string[]
     */
    private function getKeys(string $path): array
    {
        $current = -1;
        $index = 0;
        $paths = [];
        do {
            $current++;
            $symbol = mb_substr($path, $current, 1);
            $prevSymbol = mb_substr($path, $current - 1, 1);

            if ($symbol === (string) static::PATH_SEPARATOR && $prevSymbol !== '\\') {
                $index++;

                continue;
            }
            if (!isset($paths[$index])) {
                $paths[$index] = '';
            }
            if ($symbol === (string) static::PATH_SEPARATOR && $prevSymbol === '\\') {
                $paths[$index] = mb_substr($paths[$index], 0, -1);
            }
            /**
             * @psalm-suppress PossiblyUndefinedArrayOffset
             */
            $paths[$index] .= $symbol;
        } while ($current < mb_strlen($path));

        return $paths;
    }

    /**
     * Возвращает плоский список значений
     *
     * @return IValue[]
     */
    private function flatten(IValue $value): array
    {
        if ($value->isWildcard()) {
            $result = [];
            foreach ($value->getValue() as $item) {
                assert($item instanceof IValue);
                $result = array_merge($result, $this->flatten($item));
            }

            return $result;
        }

        return [$value];
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
         * @var IRule $rule
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
    public function addRule($rule): IChain
    {
        if (!($rule instanceof IRule) && !($rule instanceof IChain)) {
            throw new InvalidArgumentException('The rule must implement the interface ' . IRule::class);
        }

        $rules = $this->getRules();
        $rules[] = $rule;
        $this->setRules($rules);

        return $this;
    }
}
