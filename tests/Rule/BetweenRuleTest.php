<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на максимальное и мимальное значение
 */
class BetweenRuleTest extends TestCase
{
    /**
     * Проверка на максимальное и мимальное значение
     */
    public function testBetween(): void
    {
        $this->assertTrue(AllOf::create()->between(100, 200)->validate(150)->isSuccess());
        $this->assertFalse(AllOf::create()->between(100, 200)->validate(300)->isSuccess());
        $this->assertFalse(AllOf::create()->between(100, 200)->validate('abc')->isSuccess());
    }

    /**
     * Проверка на максимальное и мимальное значение
     */
    public function testBetweenValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 150], ['foo' => 'between(100, 200)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 201]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'between(100, 200)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Проверка на максимальное и мимальное значение
     */
    public function testMinArgumentException(): void
    {
        $validator = new Validator();
        $this->expectException(InvalidArgumentException::class);
        $validator->make(['foo' => 200], ['foo' => 'between("abc", 200)']);
    }

    /**
     * Проверка на максимальное и мимальное значение
     */
    public function testMaxArgumentException(): void
    {
        $validator = new Validator();
        $this->expectException(InvalidArgumentException::class);
        $validator->make(['foo' => 200], ['foo' => 'between(100, "abc")']);
    }

    /**
     * Проверка на максимальное и мимальное значение
     */
    public function testArgumentException(): void
    {
        $validator = new Validator();
        $this->expectException(InvalidArgumentException::class);
        $validator->make(['foo' => 200], ['foo' => 'between(200, 100)']);
    }
}
