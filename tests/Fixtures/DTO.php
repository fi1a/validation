<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Fixtures;

class DTO
{
    public $propertyA = 100;

    public $propertyB = 'string';

    public $propertyC;

    public $propertyD;

    public function getPropertyD(): bool
    {
        return true;
    }
}
