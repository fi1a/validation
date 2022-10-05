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
            $internalFieldName = is_null($fieldName) ? (string) $key : $fieldName;

            if ($rule instanceof IRule) {
                if (is_array($values)) {
                    $tree = $this->getValue($this->getKeys($internalFieldName), $values);
                    foreach ($tree->flatten() as $flat) {
                        [$path, $value] = $flat;
                        $success = $rule->validate($value);
                        $this->setSuccess(
                            $result,
                            $success,
                            $rule->getRuleName(),
                            (string) $path,
                            $rule->getMessages()
                        );
                    }

                    continue;
                }
                /**
                 * @var mixed $value
                 */
                $value = $values;
                $success = $rule->validate($value);
                $this->setSuccess(
                    $result,
                    $success,
                    $rule->getRuleName(),
                    $internalFieldName,
                    $rule->getMessages()
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
     * @param string[] $paths
     * @param mixed $values
     */
    protected function getValue(array $paths, $values, ?string $realPath = null): IValue
    {
        $path = array_shift($paths);
        if (is_null($realPath)) {
            $realPath = '';
        }
        if ($path !== '*') {
            $realPath .= ($realPath ? ':' : '') . $path;
        }
        $return = new Value();
        if ($path === '*') {
            if (!is_array($values)) {
                $return->setPath($realPath);

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
                    $realPath . ($realPath ? ':' : '') . $index
                );
            }

            $return->setValue($result);
            $return->setArrayAttribute(true);
            $return->setPath($realPath);

            return $return;
        }
        if (!is_array($values) || !array_key_exists($path, $values)) {
            $return->setPath($realPath);

            return $return;
        }
        if (count($paths) > 0) {
            return $this->getValue($paths, $values[$path], $realPath);
        }

        $return->setValue($values[$path]);
        $return->setPath($realPath);

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
}
