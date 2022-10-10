<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Не допустимые значения
 */
class NotInRuleTest extends TestCase
{
    /**
     * Не допустимые значения
     */
    public function testNotIn(): void
    {
        $this->assertTrue(AllOf::create()->notIn(1, 2, 3)->validate(4)->isSuccess());
        $this->assertFalse(AllOf::create()->notIn(1, 2, 3)->validate(2)->isSuccess());
    }

    /**
     * Не допустимые значения
     */
    public function testNotInValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 4], ['foo' => 'notIn(1, 2, 3)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 2]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'notIn(1, 2, 3)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Исключение при пустых не допустимых значениях
     */
    public function testNotInException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $validator = new Validator();
        $validation = $validator->make(['foo' => 1], ['foo' => 'notIn()']);
        $validation->validate();
    }
}
