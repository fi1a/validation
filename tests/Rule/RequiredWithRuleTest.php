<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Обязательное значение, если есть значения в полях
 */
class RequiredWithRuleTest extends TestCase
{
    /**
     * Обязательное значение, если есть значения в полях
     */
    public function testRequiredWith(): void
    {
        $this->assertTrue(
            AllOf::create()
                ->requiredWith('array:foo', 'array:bar')
                ->validate(['array' => ['foo' => 'foo', 'bar' => 'bar'], 'baz' => 'baz'], 'baz')
                ->isSuccess()
        );
        $this->assertTrue(
            AllOf::create()
                ->requiredWith('array:foo', 'array:bar')
                ->validate(['array' => ['foo' => 'foo', 'bar' => null], 'baz' => null], 'baz')
                ->isSuccess()
        );
        $this->assertTrue(
            AllOf::create()
                ->requiredWith('array:foo', 'array:bar')
                ->validate(['array' => ['foo' => 'foo',], 'baz' => null], 'baz')
                ->isSuccess()
        );
        $this->assertFalse(
            AllOf::create()
                ->requiredWith('array:foo', 'array:bar')
                ->validate(['array' => ['foo' => 'foo', 'bar' => 'bar'], 'baz' => null], 'baz')
                ->isSuccess()
        );
        $this->assertFalse(
            AllOf::create()
                ->requiredWith('array:*')
                ->validate(['array' => [['foo' => 'foo', 'bar' => null]], 'baz' => null], 'baz')
                ->isSuccess()
        );
    }

    /**
     * Обязательное значение, если есть значения в полях
     */
    public function testRequiredWithValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            ['array' => ['foo' => 'foo', 'bar' => 'bar'], 'baz' => 'baz'],
            ['baz' => 'requiredWith("array:foo", "array:bar")']
        );
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['array' => ['foo' => 'foo', 'bar' => 'bar'], 'baz' => null]);
        $this->assertFalse($validation->validate()->isSuccess());
    }

    /**
     * Исключение при пустых полях
     */
    public function testRequiredWithFieldException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        AllOf::create()->requiredWith();
    }
}
