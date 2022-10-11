<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение email адресом
 */
class EmailRuleTest extends TestCase
{
    /**
     * Является ли значение email адресом
     */
    public function testEmail(): void
    {
        $this->assertTrue(AllOf::create()->email()->validate('foo@bar.ru')->isSuccess());
        $this->assertFalse(AllOf::create()->email()->validate('foo')->isSuccess());
    }

    /**
     * Является ли значение email адресом
     */
    public function testEmailValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 'foo@bar.ru'], ['foo' => 'email']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 'bar']);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'email']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
