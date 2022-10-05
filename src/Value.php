<?php

declare(strict_types=1);

namespace Fi1a\Validation;

class Value implements IValue
{
    /**
     * @var mixed|IValue|null
     */
    private $value;

    /**
     * @var bool
     */
    private $arrayAttribute = false;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @inheritDoc
     */
    public function setValue($value): bool
    {
        $this->value = $value;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function isArrayAttribute(): bool
    {
        return $this->arrayAttribute;
    }

    /**
     * @inheritDoc
     */
    public function setArrayAttribute(bool $arrayAttribute): bool
    {
        $this->arrayAttribute = $arrayAttribute;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function setPath(string $path): bool
    {
        $this->path = $path;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function flatten(): array
    {
        if ($this->isArrayAttribute()) {
            $result = [];
            foreach ($this->getValue() as $value) {
                assert($value instanceof IValue);
                $result = array_merge($result, $value->flatten());
            }

            return $result;
        }

        return [[$this->getPath(), $this->getValue()]];
    }
}
