<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

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
}
