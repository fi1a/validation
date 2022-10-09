<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на минимальное значение
 */
class MinRuleTest extends TestCase
{
    /**
     * Проверка на минимальное значение
     */
    public function testMin(): void
    {
        $this->assertTrue(AllOf::create()->min(100)->validate(200)->isSuccess());
        $this->assertFalse(AllOf::create()->min(200)->validate(100)->isSuccess());
        $this->assertFalse(AllOf::create()->min(200)->validate('abc')->isSuccess());
    }

    /**
     * Проверка на минимальное значение
     */
    public function testMinValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 200], ['foo' => 'min(100)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 0]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'min(100)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Исключение
     */
    public function testMinArgumentException(): void
    {
        $validator = new Validator();
        $this->expectException(InvalidArgumentException::class);
        $validator->make(['foo' => 200], ['foo' => 'min("abc")']);
    }
}
