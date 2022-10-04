<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Validation\Error;
use PHPUnit\Framework\TestCase;

/**
 * Класс ошибки
 */
class ErrorTest extends TestCase
{
    /**
     * Методы класса ошибки
     */
    public function testError(): void
    {
        $error = new Error('required', 'field1', 'message');
        $this->assertEquals('field1', $error->getFieldName());
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('message', $error->getMessage());
    }
}
