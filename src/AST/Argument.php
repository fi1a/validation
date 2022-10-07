<?php

declare(strict_types=1);

namespace Fi1a\Validation\AST;

/**
 * Аргумент правила
 */
class Argument implements IArgument
{
    /**
     * @var int|string|bool|null
     */
    private $value;

    /**
     * @inheritDoc
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->value;
    }
}
