<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на максимальное количество элементов в массиве
 */
class MaxCountRuleTest extends TestCase
{
    /**
     * Проверка на максимальное количество элементов в массиве
     */
    public function testMaxCount(): void
    {
        $this->assertTrue(AllOf::create()->maxCount(2)->validate([1,])->isSuccess());
        $this->assertFalse(AllOf::create()->maxCount(2)->validate(100)->isSuccess());
        $this->assertFalse(AllOf::create()->maxCount(2)->validate([1, 2, 3])->isSuccess());
    }

    /**
     * Проверка на максимальное количество элементов в массиве
     */
    public function testMaxCountValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => [1,]], ['foo' => 'maxCount(2)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => [1, 2, 3]]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'maxCount(2)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
