<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Validation\Error;
use Fi1a\Validation\Result;
use PHPUnit\Framework\TestCase;

/**
 * Результат валидации
 */
class ResultTest extends TestCase
{
    /**
     * Методы связанные с результатом
     */
    public function testSuccess(): void
    {
        $result = new Result();
        $this->assertTrue($result->setSuccess(true));
        $this->assertTrue($result->isSuccess());
        $this->assertTrue($result->addError(new Error('required')));
        $this->assertTrue($result->setSuccess(false));
        $this->assertFalse($result->isSuccess());
    }

    /**
     * Методы связанные с ошибками
     */
    public function testErrors(): void
    {
        $result = new Result();
        $this->assertTrue($result->addError(new Error('required')));
        $this->assertTrue($result->addErrors([new Error('required')]));
        $this->assertCount(2, $result->getErrors());
    }
}
