<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Допустимые значения (строгая проверка)
 */
class StrictInRuleTest extends TestCase
{
    /**
     * Допустимые значения (строгая проверка)
     */
    public function testIn(): void
    {
        $this->assertTrue(AllOf::create()->strictIn(1, 2, 3)->validate(1)->isSuccess());
        $this->assertTrue(AllOf::create()->strictIn([1, 2, 3])->validate(1)->isSuccess());
        $this->assertFalse(AllOf::create()->strictIn(1, 2, 3)->validate('1')->isSuccess());
        $this->assertFalse(AllOf::create()->strictIn(1, 2, 3)->validate(100.1)->isSuccess());
    }

    /**
     * Допустимые значения (строгая проверка)
     */
    public function testInValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 1], ['foo' => 'strictIn(1, 2, 3)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 100.1]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'strictIn(1, 2, 3)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Исключение при пустых допустимых значениях
     */
    public function testInException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $validator = new Validator();
        $validation = $validator->make(['foo' => 1], ['foo' => 'strictIn()']);
        $validation->validate();
    }
}
