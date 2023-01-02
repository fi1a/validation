<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на минимальное значение даты
 */
class MinDateRuleTest extends TestCase
{
    /**
     * Проверка на минимальное значение даты
     */
    public function testMinDate(): void
    {
        $this->assertTrue(
            AllOf::create()->minDate('10.10.2022 10:10:10')->validate('10.10.2022 10:10:10')->isSuccess()
        );
        $this->assertFalse(
            AllOf::create()->minDate('10.10.2022 10:10:10')->validate('10.10.2022 09:00:00')->isSuccess()
        );
        $this->assertFalse(
            AllOf::create()->minDate('10.10.2022 10:10:10')->validate('abc')->isSuccess()
        );
    }

    /**
     * Проверка на минимальное значение даты
     */
    public function testMinDateValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => '11.10.2022'], ['foo' => 'minDate("10.10.2022", "d.m.Y")']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 0]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'minDate("10.10.2022", "d.m.Y")']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
