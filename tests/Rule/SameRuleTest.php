<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Совпадают ли значения с указанным полем
 */
class SameRuleTest extends TestCase
{
    /**
     * Совпадают ли значения с указанным полем
     */
    public function testSame(): void
    {
        $result = AllOf::create()
            ->setTitles(['field1' => 'Name field1'])
            ->same('field1')
            ->validate(200);

        $this->assertFalse($result->isSuccess());
        $this->assertEquals('Значение должно совпадать с "Name field1"', $result->getErrors()->first()->getMessage());

        $this->assertTrue(
            AllOf::create()
                ->same('bar')
                ->validate(['foo' => 200, 'bar' => 200], 'foo')
                ->isSuccess()
        );
    }

    /**
     * Совпадают ли значения с указанным полем
     */
    public function testSameValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => 200, 'bar' => 200], ['foo' => 'same("bar")']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 200, 'bar' => 300]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'same("bar")']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Исключение
     */
    public function testSameArgumentException(): void
    {
        $validator = new Validator();
        $this->expectException(InvalidArgumentException::class);
        $validator->make(['foo' => 200], ['foo' => 'same("")']);
    }

    /**
     * Исключение
     */
    public function testSameArrayException(): void
    {
        $validator = new Validator();
        $this->expectException(InvalidArgumentException::class);
        $validation = $validator->make(
            ['foo' => 200, 'bar' => [['id' => 1], ['id' => 2,]]],
            ['foo' => 'same("bar:*:id")']
        );
        $validation->validate();
    }
}
