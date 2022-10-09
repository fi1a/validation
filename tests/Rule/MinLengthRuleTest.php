<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на минимальную длину строки
 */
class MinLengthRuleTest extends TestCase
{
    /**
     * Проверка на минимальную длину строки
     */
    public function testMin(): void
    {
        $this->assertTrue(AllOf::create()->minLength(5)->validate('123456')->isSuccess());
        $this->assertTrue(AllOf::create()->minLength(5)->validate(123456)->isSuccess());
        $this->assertFalse(AllOf::create()->minLength(5)->validate(100)->isSuccess());
        $this->assertFalse(AllOf::create()->minLength(5)->validate('abc')->isSuccess());
    }

    /**
     * Проверка на минимальную длину строки
     */
    public function testMinValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 'abc def gh'], ['foo' => 'minLength(5)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 0]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'minLength(5)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
