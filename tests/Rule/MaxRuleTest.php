<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на максимальное значение
 */
class MaxRuleTest extends TestCase
{
    /**
     * Проверка на максимальное значение
     */
    public function testMax(): void
    {
        $this->assertTrue(AllOf::create()->max(100)->validate(50)->isSuccess());
        $this->assertFalse(AllOf::create()->max(200)->validate(300)->isSuccess());
        $this->assertFalse(AllOf::create()->max(200)->validate('abc')->isSuccess());
    }

    /**
     * Проверка на максимальное значение
     */
    public function testMaxValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 50], ['foo' => 'max(100)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 200]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'max(100)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
