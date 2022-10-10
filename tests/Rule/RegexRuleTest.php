<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на регулярно выражение
 */
class RegexRuleTest extends TestCase
{
    /**
     * Проверка на регулярно выражение
     */
    public function testRegex(): void
    {
        $this->assertTrue(AllOf::create()->regex('/[0-9]/mui')->validate(200)->isSuccess());
        $this->assertFalse(AllOf::create()->regex('/[0-9]/mui')->validate('abc')->isSuccess());
    }

    /**
     * Проверка на регулярно выражение
     */
    public function testRegexValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => '200'], ['foo' => 'regex("/[0-9]/mui")']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 'abc']);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'regex("/[0-9]/mui")']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Исключение
     */
    public function testRegexArgumentException(): void
    {
        $validator = new Validator();
        $this->expectException(InvalidArgumentException::class);
        $validator->make(['foo' => 200], ['foo' => 'regex("")']);
    }
}
