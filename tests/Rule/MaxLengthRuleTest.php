<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на максимальную длину строки
 */
class MaxLengthRuleTest extends TestCase
{
    /**
     * Проверка на максимальную длину строки
     */
    public function testMaxLength(): void
    {
        $this->assertTrue(AllOf::create()->maxLength(5)->validate('123')->isSuccess());
        $this->assertTrue(AllOf::create()->maxLength(5)->validate(123)->isSuccess());
        $this->assertFalse(AllOf::create()->maxLength(5)->validate(1000000)->isSuccess());
        $this->assertFalse(AllOf::create()->maxLength(5)->validate('abc def h')->isSuccess());
    }

    /**
     * Проверка на максимальную длину строки
     */
    public function testMaxLengthValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 0], ['foo' => 'maxLength(5)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 'abc def gh']);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'maxLength(5)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
