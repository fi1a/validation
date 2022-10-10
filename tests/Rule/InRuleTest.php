<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Допустимые значения
 */
class InRuleTest extends TestCase
{
    /**
     * Допустимые значения
     */
    public function testIn(): void
    {
        $this->assertTrue(AllOf::create()->in(1, 2, 3)->validate(1)->isSuccess());
        $this->assertFalse(AllOf::create()->in(1, 2, 3)->validate(100.1)->isSuccess());
    }

    /**
     * Допустимые значения
     */
    public function testInValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 1], ['foo' => 'in(1, 2, 3)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 100.1]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'in(1, 2, 3)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Исключение при пустых допустимых значениях
     */
    public function testInException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $validator = new Validator();
        $validation = $validator->make(['foo' => 1], ['foo' => 'in()']);
        $validation->validate();
    }
}
