<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на минимальное и максимальное значение даты
 */
class BetweenDateRuleTest extends TestCase
{
    /**
     * Проверка на минимальное и максимальное значение даты
     */
    public function testBetweenDate(): void
    {
        $this->assertTrue(
            AllOf::create()
                ->betweenDate('10.10.2022 10:10:10', '12.10.2022 10:10:10')
                ->validate('11.10.2022 10:10:10')
                ->isSuccess()
        );
        $this->assertFalse(
            AllOf::create()
                ->betweenDate('10.10.2022 10:10:10', '12.10.2022 10:10:10')
                ->validate('10.10.2022 09:00:00')
                ->isSuccess()
        );
        $this->assertFalse(
            AllOf::create()
                ->betweenDate('10.10.2022 10:10:10', '12.10.2022 10:10:10')
                ->validate('abc')
                ->isSuccess()
        );
    }

    /**
     * Проверка на минимальное и максимальное значение даты
     */
    public function testBetweenDateValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            ['foo' => '11.10.2022'],
            ['foo' => 'betweenDate("10.10.2022", "12.10.2022", "d.m.Y")']
        );
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 0]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'betweenDate("10.10.2022", "12.10.2022", "d.m.Y")']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
