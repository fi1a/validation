<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Значение
 */
class Value implements ValueInterface
{
    /**
     * @var mixed|ValueInterface|null
     */
    private $value;

    /**
     * @var bool
     */
    private $wildcard = false;

    /**
     * @var bool
     */
    private $wildcardItem = false;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @var string|null
     */
    private $validationPath;

    /**
     * @var bool
     */
    private $presence = false;

    /**
     * @var string|null
     */
    private $ruleName = null;

    /**
     * @var bool|null
     */
    private $valid;

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
    public function isWildcard(): bool
    {
        return $this->wildcard;
    }

    /**
     * @inheritDoc
     */
    public function setWildcard(bool $wildcard): bool
    {
        $this->wildcard = $wildcard;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function isWildcardItem(): bool
    {
        return $this->wildcardItem;
    }

    /**
     * @inheritDoc
     */
    public function setWildcardItem(bool $wildcardItem): bool
    {
        $this->wildcardItem = $wildcardItem;

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
    public function setWildcardPath(string $path): bool
    {
        $this->validationPath = $path;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getWildcardPath(): ?string
    {
        return $this->validationPath;
    }

    /**
     * @inheritDoc
     */
    public function setPresence(bool $presence): bool
    {
        $this->presence = $presence;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function isPresence(): bool
    {
        return $this->presence;
    }

    /**
     * @inheritDoc
     */
    public function setRuleName(string $ruleName): bool
    {
        $this->ruleName = $ruleName;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getRuleName(): ?string
    {
        return $this->ruleName;
    }

    /**
     * @inheritDoc
     */
    public function isValid(): ?bool
    {
        return $this->valid;
    }

    /**
     * @inheritDoc
     */
    public function setValid(bool $valid): bool
    {
        $this->valid = $valid;

        return true;
    }
}
