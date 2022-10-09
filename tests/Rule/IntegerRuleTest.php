<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение целым числом
 */
class IntegerRuleTest extends TestCase
{
    /**
     * Является ли значение целым числом
     */
    public function testInteger(): void
    {
        $this->assertTrue(AllOf::create()->integer()->validate(1)->isSuccess());
        $this->assertFalse(AllOf::create()->integer()->validate(100.1)->isSuccess());
    }

    /**
     * Является ли значение целым числом
     */
    public function testIntegerValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 1], ['foo' => 'integer']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 100.1]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'integer']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
