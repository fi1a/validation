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
            ['field' => null,],
            ['field' => new AllOf([new Required(), new Required(),])],
            []
        );
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($validation->validate()->isSuccess());
    }

    /**
     * Валидация
     */
    public function testValidateKeysSuccess(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            [
                'array' => [
                    [
                        'id' => 'id1',
                        'name' => 'name1',
                    ],
                    [
                        'id' => 'id2',
                        'name' => 'name2',
                    ],
                    [
                        'id' => 'id3',
                        'name' => 'name3',
                    ],
                ],
                'key' => 'key1',
            ],
            [
                'array:*:id' => new Required(),
                'array:*:name' => new Required(),
                'key' => new Required(),
            ],
            []
        );
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Валидация
     */
    public function testValidateKeysNotSuccess(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            [
                'array1' => [
                    [
                        'id' => 'id1',
                        'name' => 'name1',
                        'array2' => [
                            [
                                'foo' => 'foo1',
                            ],
                            [
                                'foo' => 'foo2',
                            ],
                        ],
                    ],
                    [
                        'id' => 'id2',
                        'name' => 'name2',
                        'array2' => [
                            [
                                'foo' => 'foo3',
                            ],
                        ],
                    ],
                    [
                        'name' => 'name2',
                        'array2' => [
                            [
                                'foo' => 'foo4',
                            ],
                            [
                                'foo' => 'foo5',
                            ],
                        ],
                    ],
                ],
                'key:key' => 'key1',
            ],
            [
                'array1:*:id' => new Required(),
                'array1:*:name' => new Required(),
                'array1:*:array2:*:foo' => new Required(),
                'key\:key' => new Required(),
            ],
            []
        );
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($validation->validate()->isSuccess());
    }

    /**
     * Валидация
     */
    public function testValidateKeysFlattenNotSuccess(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            [
                'array1' => [
                    'array2' => [
                        [
                            'id' => ['id1', 'id2'],
                        ],
                        [
                            'id' => ['id2', 'id3'],
                        ],
                        [
                        ],
                    ],
                ],
            ],
            [
                'array1:array2:*:id' => new Required(),
            ],
            []
        );
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($validation->validate()->isSuccess());
    }

    /**
     * Валидация
     */
    public function testValidateKeysFlattenSuccess(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            [
                'array1' => [
                    'array2' => [
                        [
                            'id' => ['id1', 'id2'],
                        ],
                        [
                            'id' => ['id2', 'id3'],
                        ],
                    ],
                ],
            ],
            [
                'array1:array2:*:id' => new Required(),
            ],
            []
        );
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Валидация
     */
    public function testValidateKeysFlattenEmptyArray(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            [
                'array1' => [
                    'array2' => 'id1',
                ],
            ],
            [
                'array1:array2:*:id' => new Required(),
            ],
            []
        );
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($validation->validate()->isSuccess());
    }
}
