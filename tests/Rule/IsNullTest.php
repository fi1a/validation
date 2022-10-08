<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Является ли значение null
 */
class IsNullTest extends TestCase
{
    /**
     * Является ли значение null
     */
    public function testIsNull(): void
    {
        $this->assertTrue(AllOf::create()->isNull()->validate(null)->isSuccess());
        $this->assertFalse(AllOf::create()->isNull()->validate(false)->isSuccess());
    }

    /**
     * Является ли значение null
     */
    public function testIsNullValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => null], ['foo' => 'isNull']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => false]);
        $this->assertFalse($validation->validate()->isSuccess());
    }
}
