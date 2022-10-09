<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на минимальное количество элементов в массиве
 */
class MinCountRuleTest extends TestCase
{
    /**
     * Проверка на минимальное количество элементов в массиве
     */
    public function testMinCount(): void
    {
        $this->assertTrue(AllOf::create()->minCount(2)->validate([1, 2, 3])->isSuccess());
        $this->assertFalse(AllOf::create()->minCount(2)->validate(100)->isSuccess());
        $this->assertFalse(AllOf::create()->minCount(2)->validate([1])->isSuccess());
    }

    /**
     * Проверка на минимальное количество элементов в массиве
     */
    public function testMinCountValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => [1, 2, 3]], ['foo' => 'minCount(2)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => [1]]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'minCount(2)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
