<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Значение должно быть буквенно-цифровым
 */
class AlphaNumericRuleTest extends TestCase
{
    /**
     * Значение должно быть буквенно-цифровым
     */
    public function testAlphaNumeric(): void
    {
        $this->assertTrue(AllOf::create()->alphaNumeric()->validate('123')->isSuccess());
        $this->assertTrue(AllOf::create()->alphaNumeric()->validate('abc')->isSuccess());
        $this->assertTrue(AllOf::create()->alphaNumeric()->validate('123abc')->isSuccess());
        $this->assertTrue(AllOf::create()->alphaNumeric()->validate('abc123')->isSuccess());
        $this->assertFalse(AllOf::create()->alphaNumeric()->validate(false)->isSuccess());
        $this->assertFalse(AllOf::create()->alphaNumeric()->validate('abc 123')->isSuccess());
        $this->assertFalse(AllOf::create()->alphaNumeric()->validate('123 abc')->isSuccess());
        $this->assertFalse(AllOf::create()->alphaNumeric()->validate(' 123 abc')->isSuccess());
    }

    /**
     * Значение должно быть буквенно-цифровым
     */
    public function testAlphaNumericValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 'abc 100'], ['foo' => 'alphaNumeric']);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 'abc']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'alphaNumeric']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
