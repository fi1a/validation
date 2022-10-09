<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение логическим
 */
class BooleanRuleTest extends TestCase
{
    /**
     * Является ли значение логическим
     */
    public function testBoolean(): void
    {
        $this->assertTrue(AllOf::create()->boolean()->validate(true)->isSuccess());
        $this->assertTrue(AllOf::create()->boolean()->validate(false)->isSuccess());
        $this->assertTrue(AllOf::create()->boolean()->validate('TRUE')->isSuccess());
        $this->assertTrue(AllOf::create()->boolean()->validate('FALSE')->isSuccess());
        $this->assertTrue(AllOf::create()->boolean()->validate('0')->isSuccess());
        $this->assertTrue(AllOf::create()->boolean()->validate('1')->isSuccess());
        $this->assertTrue(AllOf::create()->boolean()->validate(0)->isSuccess());
        $this->assertTrue(AllOf::create()->boolean()->validate(1)->isSuccess());
        $this->assertTrue(AllOf::create()->boolean()->validate('Y')->isSuccess());
        $this->assertTrue(AllOf::create()->boolean()->validate('N')->isSuccess());
        $this->assertFalse(AllOf::create()->boolean()->validate(100)->isSuccess());
        $this->assertFalse(AllOf::create()->boolean()->validate('abc')->isSuccess());
    }

    /**
     * Является ли значение логическим
     */
    public function testBooleanValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => true], ['foo' => 'boolean']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 'abc']);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'boolean']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
