<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на равенство
 */
class EqualRuleTest extends TestCase
{
    /**
     * Проверка на равенство
     */
    public function testEqual(): void
    {
        $this->assertTrue(AllOf::create()->equal(100)->validate(100)->isSuccess());
        $this->assertFalse(AllOf::create()->equal(100)->validate(200)->isSuccess());
        $this->assertFalse(AllOf::create()->equal(100)->validate('abc')->isSuccess());
    }

    /**
     * Проверка на равенство
     */
    public function testEqualValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 200], ['foo' => 'equal(200)']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 0]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'equal(100)']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
