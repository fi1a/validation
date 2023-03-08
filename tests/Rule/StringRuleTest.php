<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение строкой
 */
class StringRuleTest extends TestCase
{
    /**
     * Является ли значение строкой
     */
    public function testString(): void
    {
        $this->assertTrue(AllOf::create()->string()->validate('foo')->isSuccess());
        $this->assertFalse(AllOf::create()->string()->validate(false)->isSuccess());
        $this->assertFalse(AllOf::create()->string()->validate(100)->isSuccess());
    }

    /**
     * Является ли значение строкой
     */
    public function testStringValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 'foo'], ['foo' => 'string']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => false]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'string']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
