<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на максимальную и минимальную длину строки
 */
class BetweenLengthRuleTest extends TestCase
{
    /**
     * Проверка на максимальную и минимальную длину строки
     */
    public function testBetweenLength(): void
    {
        $this->assertTrue(AllOf::create()->betweenLength(2, 5)->validate(150)->isSuccess());
        $this->assertFalse(AllOf::create()->betweenLength(2, 5)->validate(3000000)->isSuccess());
        $this->assertFalse(AllOf::create()->betweenLength(2, 5)->validate('abc def gh')->isSuccess());
    }

    /**
     * Проверка на максимальную и минимальную длину строки
     */
    public function testBetweenLengthValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 150], ['foo' => 'betweenLength(2, 5)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 20000000]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'betweenLength(2, 5)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Проверка на максимальную и минимальную длину строки
     */
    public function testArgumentException(): void
    {
        $validator = new Validator();
        $this->expectException(InvalidArgumentException::class);
        $validator->make(['foo' => 200], ['foo' => 'betweenLength(5, 2)']);
    }
}
