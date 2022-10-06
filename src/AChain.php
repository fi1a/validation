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
    public function validate($values, ?string $fieldName = null): IResult
    {
        $result = new Result();

        foreach ($this->getRules() as $key => $rule) {
            $internalFieldName = is_null($fieldName) ? (string) $key : $fieldName;

            if ($rule instanceof IRule) {
                if (is_array($values)) {
                    $tree = $this->getValue($this->getKeys($internalFieldName), $values);
                    foreach ($tree->flatten() as $flat) {
                        [$path, $validationPath, $value] = $flat;
                        $success = $rule->validate($value);
                        $messages = $this->formatMessages($rule, (string) $path, (string) $validationPath);
                        $this->setSuccess(
                            $result,
                            $success,
                            $rule::getRuleName(),
                            (string) $path,
                            $messages
                        );
                    }

                    continue;
                }
                /**
                 * @var mixed $value
                 */
                $value = $values;
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
    protected function formatMessages(IRule $rule, string $fieldName, string $validationPath): array
    {
        $userMessages = $this->getMessages();
        $messages = $rule->getMessages();
        $variables = $rule->getVariables();
        $variables['name'] = $fieldName;
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
    protected function getValue(array $paths, $values, ?string $realPath = null, ?string $validationPath = null): IValue
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
                $return->setValidationPath($validationPath);

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
            $return->setArrayAttribute(true);
            $return->setPath($realPath);
            $return->setValidationPath($validationPath);

            return $return;
        }
        if (!is_array($values) || !array_key_exists($path, $values)) {
            $return->setPath($realPath);
            $return->setValidationPath($validationPath);

            return $return;
        }
        if (count($paths) > 0) {
            return $this->getValue($paths, $values[$path], $realPath, $validationPath);
        }

        $return->setValue($values[$path]);
        $return->setPath($realPath);
        $return->setValidationPath($validationPath);

        return $return;
    }

    /**
     * Возвращает массив ключей
     *
     * @param string $path путь
     *
     * @return string[]
     */
    protected function getKeys(string $path): array
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
}
