<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение null
 */
class NullRuleTest extends TestCase
{
    /**
     * Является ли значение null
     */
    public function testIsNull(): void
    {
        $this->assertTrue(AllOf::create()->null()->validate(null)->isSuccess());
        $this->assertFalse(AllOf::create()->null()->validate(false)->isSuccess());
    }

    /**
     * Является ли значение null
     */
    public function testIsNullValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => null], ['foo' => 'null']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => false]);
        $this->assertFalse($validation->validate()->isSuccess());
    }
}
