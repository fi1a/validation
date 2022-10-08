<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Обязательное значение
 */
class RequiredTest extends TestCase
{
    /**
     * Обязательное значение
     */
    public function testRequired(): void
    {
        $this->assertTrue(AllOf::create()->required()->validate(true)->isSuccess());
        $this->assertFalse(AllOf::create()->required()->validate(null)->isSuccess());
    }

    /**
     * Обязательное значение
     */
    public function testRequiredValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => true], ['foo' => 'required']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => null]);
        $this->assertFalse($validation->validate()->isSuccess());
    }
}
