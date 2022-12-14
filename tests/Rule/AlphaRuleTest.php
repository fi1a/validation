<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение только буквенным(без чисел)
 */
class AlphaRuleTest extends TestCase
{
    /**
     * Является ли значение только буквенным(без чисел)
     */
    public function testAlpha(): void
    {
        $this->assertTrue(AllOf::create()->alpha()->validate('abc')->isSuccess());
        $this->assertFalse(AllOf::create()->alpha()->validate(100)->isSuccess());
        $this->assertFalse(AllOf::create()->alpha()->validate('abc100')->isSuccess());
    }

    /**
     * Является ли значение только буквенным(без чисел)
     */
    public function testAlphaValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 'abc'], ['foo' => 'alpha']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => false]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'alpha']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
