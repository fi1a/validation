<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Closure;
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
        $result = new Result();
        if (is_null($fieldName)) {
            $this->setInternalAsArray(false);
        }

        foreach ($this->getRules() as $key => $rule) {
            $internalFieldName = is_null($fieldName) || $fieldName === false ? (string) $key : (string) $fieldName;

            if ($rule instanceof RuleInterface) {
                if (is_array($values) && $this->internalAsArray === true) {
                    $tree = $this->getValue($this->getKeys($internalFieldName), $values);
                    foreach ($this->flatten($tree) as $value) {
                        $success = $rule->validate($value);
                        $messages = $this->formatMessages($rule, $value);
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
                $messages = $this->formatMessages($rule, $value);
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

        $this->setInternalAsArray(true);

        return $result;
    }

    /**
     * Не использовать значение как массив
     */
    protected function setInternalAsArray(bool $internalAsArray): void
    {
        $this->internalAsArray = $internalAsArray;
        foreach ($this->getRules() as $rule) {
            if ($rule instanceof ChainInterface) {
                $func = Closure::bind(function (bool $internalAsArray) {
                    $this->setInternalAsArray($internalAsArray);
                }, $rule, get_class($rule));
                /** @psalm-suppress PossiblyInvalidFunctionCall */
                $func($internalAsArray);
            }
        }
    }

    /**
     * Форматирование сообщений
     *
     * @return string[]
     */
    private function formatMessages(RuleInterface $rule, ValueInterface $value): array
    {
        $userMessages = $this->getMessages();
        $messages = $rule->getMessages();
        $titles = $this->getTitles();
        $fieldTitle = (string) $value->getPath();
        if (array_key_exists((string) $value->getPath(), $titles)) {
            $fieldTitle = $titles[(string) $value->getPath()];
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
     * @param string[] $paths
     * @param mixed $values
     */
    private function getValue(
        array $paths,
        $values,
        ?string $realPath = null,
        ?string $validationPath = null
    ): ValueInterface {
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
     * @return ValueInterface[]
     */
    private function flatten(ValueInterface $value): array
    {
        if ($value->isWildcard()) {
            $result = [];
            foreach ($value->getValue() as $item) {
                assert($item instanceof ValueInterface);
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
