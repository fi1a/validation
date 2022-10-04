<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Rule\Required;
use Fi1a\Validation\Validation;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Валидатор
 */
class ValidatorTest extends TestCase
{
    /**
     * Создать класс проверки значений
     */
    public function testMakeEmpty(): void
    {
        $validator = new Validator();
        $validation = $validator->make([], [], []);
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertEquals($validator, $validation->getValidator());
    }

    /**
     * Валидация
     */
    public function testValidateRequiredFalse(): void
    {
        $validator = new Validator();
        $validation = $validator->make([], ['field' => new Required()], []);
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($validation->validate()->isSuccess());
    }

    /**
     * Валидация
     */
    public function testValidateRequiredTrue(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['field' => 1,], ['field' => new Required()], []);
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Валидация
     */
    public function testValidateAllOfAndRequiredTrue(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            ['field' => 1,],
            ['field' => new AllOf([new Required(), new Required(),])],
            []
        );
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Валидация
     */
    public function testValidateAllOfAndRequiredFalse(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            ['field' => false,],
            ['field' => new AllOf([new Required(), new Required(),])],
            []
        );
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($validation->validate()->isSuccess());
    }
}
