<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

/**
 * Абстрактное правило даты и времени
 */
abstract class AbstractDateRule extends AbstractRule
{
    /**
     * @var string
     */
    protected static $defaultFormat = 'd.m.Y H:i:s';
}
