<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Значения
 */
class Values implements ValuesInterface
{
    /**
     * @var mixed
     */
    private $values;

    /**
     * @var bool
     */
    private $asArray = true;

    /**
     * @inheritDoc
     */
    public function __construct($values)
    {
        $this->values = $values;
    }

    /**
     * @inheritDoc
     */
    public function getValue(string $fieldName)
    {
        if (!is_array($this->getValues()) || !$this->asArray()) {
            $value = new Value();
            $value->setValue($this->values);
            $value->setPath($fieldName);
            $value->setWildcardPath($fieldName);
            $value->setPresence(true);

            return $value;
        }

        $value = $this->getValueInternal($this->getKeys($fieldName), $this->values);
        if (!$value->isWildcard()) {
            return $value;
        }

        return $this->flatten($value);
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
    public function asArray(): bool
    {
        return $this->asArray;
    }

    /**
     * @inheritDoc
     */
    public function setAsArray(bool $asArray): bool
    {
        $this->asArray = $asArray;

        return true;
    }

    /**
     * @param string[] $paths
     * @param mixed $values
     */
    private function getValueInternal(
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
                $result[] = $this->getValueInternal(
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
            return $this->getValueInternal($paths, $values[$path], $realPath, $validationPath);
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
}
