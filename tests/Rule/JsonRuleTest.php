<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение json строкой
 */
class JsonRuleTest extends TestCase
{
    /**
     * Является ли значение json строкой
     */
    public function testJson(): void
    {
        $this->assertTrue(AllOf::create()->json()->validate(json_encode([1, 2, 3]))->isSuccess());
        $this->assertFalse(AllOf::create()->json()->validate('{')->isSuccess());
    }

    /**
     * Является ли значение json строкой
     */
    public function testJsonValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => json_encode([1, 2, 3])], ['foo' => 'json']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => '{']);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'json']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
