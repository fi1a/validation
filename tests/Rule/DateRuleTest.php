<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Проверка на формат даты
 */
class DateRuleTest extends TestCase
{
    /**
     * Проверка на формат даты
     */
    public function testDate(): void
    {
        $this->assertTrue(AllOf::create()->date()->validate('10.10.2022 10:10:10')->isSuccess());
        $this->assertFalse(AllOf::create()->date('d')->validate('10.10.2022')->isSuccess());
        $this->assertFalse(AllOf::create()->date()->validate('abc')->isSuccess());
    }

    /**
     * Проверка на формат даты
     */
    public function testDateValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => '10.10.2022'], ['foo' => 'date("d.m.Y")']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 0]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'date("d.m.Y")']);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
