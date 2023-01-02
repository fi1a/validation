<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение url
 */
class UrlRuleTest extends TestCase
{
    /**
     * Является ли значение url
     */
    public function testUrl(): void
    {
        $this->assertTrue(AllOf::create()->url()->validate('https://domain.ru/path/')->isSuccess());
        $this->assertFalse(AllOf::create()->url()->validate('https')->isSuccess());
        $this->assertFalse(AllOf::create()->url()->validate(100)->isSuccess());
    }

    /**
     * Является ли значение url
     */
    public function testUrlValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 'https://domain.ru/path/'], ['foo' => 'url']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 'https']);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'url']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
