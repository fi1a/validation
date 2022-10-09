<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение массивом
 */
class ArrayRuleTest extends TestCase
{
    /**
     * Является ли значение массивом
     */
    public function testArray(): void
    {
        $this->assertTrue(AllOf::create()->array()->validate([1, 2, 3])->isSuccess());
        $this->assertFalse(AllOf::create()->array()->validate(false)->isSuccess());
    }

    /**
     * Является ли значение массивом
     */
    public function testArrayValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => [1, 2, 3]], ['foo' => 'array']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => false]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation->setValues([]);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
