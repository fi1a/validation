<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на максимальное значение даты
 */
class MaxDateRuleTest extends TestCase
{
    /**
     * Проверка на максимальное значение даты
     */
    public function testMaxDate(): void
    {
        $this->assertTrue(
            AllOf::create()->maxDate('10.10.2022 10:10:10')->validate('10.10.2022 10:10:10')->isSuccess()
        );
        $this->assertTrue(
            AllOf::create()->maxDate('10.10.2022 11:10:10')->validate('10.10.2022 10:10:10')->isSuccess()
        );
        $this->assertFalse(
            AllOf::create()->maxDate('10.10.2022 10:10:10')->validate('10.10.2022 12:00:00')->isSuccess()
        );
        $this->assertFalse(
            AllOf::create()->maxDate('10.10.2022 10:10:10')->validate('abc')->isSuccess()
        );
    }

    /**
     * Проверка на максимальное значение даты
     */
    public function testMaxDateValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => '09.10.2022'], ['foo' => 'maxDate("10.10.2022", "d.m.Y")']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 0]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'maxDate("10.10.2022", "d.m.Y")']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
