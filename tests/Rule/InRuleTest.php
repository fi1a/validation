<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Разрешенные значения
 */
class InRuleTest extends TestCase
{
    /**
     * Разрешенные значения
     */
    public function testIn(): void
    {
        $this->assertTrue(AllOf::create()->in(1, 2, 3)->validate(1)->isSuccess());
        $this->assertFalse(AllOf::create()->in(1, 2, 3)->validate(100.1)->isSuccess());
    }

    /**
     * Разрешенные значения
     */
    public function testInValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 1], ['foo' => 'in(1, 2, 3)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 100.1]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'in(1, 2, 3)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
