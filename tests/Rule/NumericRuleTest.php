<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение числом
 */
class NumericRuleTest extends TestCase
{
    /**
     * Является ли значение числом
     */
    public function testNumeric(): void
    {
        $this->assertTrue(AllOf::create()->numeric()->validate(1)->isSuccess());
        $this->assertFalse(AllOf::create()->numeric()->validate(false)->isSuccess());
    }

    /**
     * Является ли значение числом
     */
    public function testNumericValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 1], ['foo' => 'numeric']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => false]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'numeric']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
