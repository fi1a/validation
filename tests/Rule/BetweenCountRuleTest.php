<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на минимальное и максимальное количество элементов в массиве
 */
class BetweenCountRuleTest extends TestCase
{
    /**
     * Проверка на минимальное и максимальное количество элементов в массиве
     */
    public function testBetweenCount(): void
    {
        $this->assertTrue(AllOf::create()->betweenCount(2, 5)->validate([1, 2, 3])->isSuccess());
        $this->assertFalse(AllOf::create()->betweenCount(2, 5)->validate(3000000)->isSuccess());
        $this->assertFalse(AllOf::create()->betweenCount(2, 5)->validate([1,])->isSuccess());
    }

    /**
     * Проверка на минимальное и максимальное количество элементов в массиве
     */
    public function testBetweenCountValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => [1, 2, 3]], ['foo' => 'betweenCount(2, 5)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => [1,]]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'betweenCount(2, 5)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Проверка на минимальное и максимальное количество элементов в массиве
     */
    public function testArgumentException(): void
    {
        $validator = new Validator();
        $this->expectException(InvalidArgumentException::class);
        $validator->make(['foo' => [1, 2, 3]], ['foo' => 'betweenCount(5, 2)']);
    }
}
