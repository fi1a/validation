<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Unit\Validation\Fixtures\FixtureRule;
use Fi1a\Validation\AllOf;
use Fi1a\Validation\Errors;
use Fi1a\Validation\Exception\RuleNotFound;
use Fi1a\Validation\IError;
use Fi1a\Validation\Rule\IsNull;
use Fi1a\Validation\Rule\Required;
use Fi1a\Validation\Validation;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
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
            ['field' => AllOf::create(new Required(), new Required())],
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
            ['field' => AllOf::create(new Required(), new Required())],
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

    /**
     * Валидация
     */
    public function testValidateCheckMessages(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            [
                'key1' => [
                    'key2' => null,
                ],
            ],
            [
                'key1:key2' => new Required(),
            ],
            [
            ]
        );
        $result = $validation->validate();
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($result->isSuccess());
        $this->assertInstanceOf(Errors::class, $result->getErrors());
        $this->assertCount(1, $result->getErrors());
        $error = $result->getErrors()[0];
        $this->assertInstanceOf(IError::class, $error);
        $this->assertEquals('Field "key1:key2" is required', $error->getMessage());
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('key1:key2', $error->getFieldName());
        $this->assertEquals('required', $error->getMessageKey());

        $validation = $validator->make(
            [
                'key1' => [
                    'key2' => null,
                ],
            ],
            [
                'key1:key2' => new Required(),
            ],
            [
                'required' => 'Test message for field "{{name}}"',
            ]
        );
        $result = $validation->validate();
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($result->isSuccess());
        $this->assertInstanceOf(Errors::class, $result->getErrors());
        $this->assertCount(1, $result->getErrors());
        $error = $result->getErrors()[0];
        $this->assertInstanceOf(IError::class, $error);
        $this->assertEquals('Test message for field "key1:key2"', $error->getMessage());
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('key1:key2', $error->getFieldName());
        $this->assertEquals('required', $error->getMessageKey());

        $validation = $validator->make(
            [
                'key1' => [
                    'key2' => null,
                ],
            ],
            [
                'key1:key2' => new Required(),
                'key1:key3' => new Required(),
            ],
            [
                'required|key1:key2' => 'Only for this field "{{name}}"',
            ]
        );
        $result = $validation->validate();
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($result->isSuccess());
        $this->assertInstanceOf(Errors::class, $result->getErrors());
        $this->assertCount(2, $result->getErrors());
        $error = $result->getErrors()[0];
        $this->assertInstanceOf(IError::class, $error);
        $this->assertEquals('Only for this field "key1:key2"', $error->getMessage());
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('key1:key2', $error->getFieldName());
        $this->assertEquals('required', $error->getMessageKey());
        $error = $result->getErrors()[1];
        $this->assertInstanceOf(IError::class, $error);
        $this->assertEquals('Field "key1:key3" is required', $error->getMessage());
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('key1:key3', $error->getFieldName());
        $this->assertEquals('required', $error->getMessageKey());
    }

    /**
     * Валидация
     */
    public function testValidateCheckMessagesDeep(): void
    {
        $validator = new Validator();

        $validation = $validator->make(
            [
                'key1' => [
                    'key2' => null,
                ],
            ],
            [
                'key1:key2' => AllOf::create()->allOf()->required(),
            ]
        );
        $validation->setMessages([
            'required' => 'Test message for field "{{name}}"',
        ]);

        $result = $validation->validate();
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($result->isSuccess());
        $this->assertInstanceOf(Errors::class, $result->getErrors());
        $this->assertCount(1, $result->getErrors());
        $error = $result->getErrors()[0];
        $this->assertInstanceOf(IError::class, $error);
        $this->assertEquals('Test message for field "key1:key2"', $error->getMessage());
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('key1:key2', $error->getFieldName());
        $this->assertEquals('required', $error->getMessageKey());
    }

    /**
     * Валидация
     */
    public function testValidateKeyCheckMessages(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            [
                'array1' => [
                    [
                        'id' => 'id1',
                    ],
                    [
                        'id' => 'id2',
                    ],
                    [],
                ],
            ],
            [
                'array1:*:id' => new Required(),
            ],
            [
            ]
        );
        $result = $validation->validate();
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($result->isSuccess());
        $this->assertInstanceOf(Errors::class, $result->getErrors());
        $this->assertCount(1, $result->getErrors());
        $error = $result->getErrors()[0];
        $this->assertInstanceOf(IError::class, $error);
        $this->assertEquals('Field "array1:2:id" is required', $error->getMessage());
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('array1:2:id', $error->getFieldName());
        $this->assertEquals('required', $error->getMessageKey());

        $validation = $validator->make(
            [
                'array1' => [
                    [
                        'id' => 'id1',
                    ],
                    [
                        'id' => 'id2',
                    ],
                    [],
                ],
            ],
            [
                'array1:*:id' => new Required(),
            ],
            [
                'required' => 'Test message "{{name}}"',
            ]
        );
        $result = $validation->validate();
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($result->isSuccess());
        $this->assertInstanceOf(Errors::class, $result->getErrors());
        $this->assertCount(1, $result->getErrors());
        $error = $result->getErrors()[0];
        $this->assertInstanceOf(IError::class, $error);
        $this->assertEquals('Test message "array1:2:id"', $error->getMessage());
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('array1:2:id', $error->getFieldName());
        $this->assertEquals('required', $error->getMessageKey());

        $validation = $validator->make(
            [],
            [
                'array1:*:id' => new Required(),
                'array1:*:name' => new Required(),
            ]
        );
        $values = [
            'array1' => [
                [
                    'id' => 'id1',
                    'name' => 'name1',
                ],
                [
                    'id' => 'id2',
                ],
                [
                    'name' => 'name3',
                ],
            ],
        ];
        $validation->setValues($values);
        $this->assertEquals($values, $validation->getValues());
        $validation->setMessages(array_merge([
            'required' => 'All "{{name}}"',
        ], $validation->getMessages()));
        $validation->setMessages(array_merge([
            'required|array1:*:id' => 'Test message "{{name}}"',
        ], $validation->getMessages()));

        $result = $validation->validate();
        $this->assertInstanceOf(Validation::class, $validation);
        $this->assertFalse($result->isSuccess());
        $this->assertInstanceOf(Errors::class, $result->getErrors());
        $this->assertCount(2, $result->getErrors());
        $error = $result->getErrors()[0];
        $this->assertInstanceOf(IError::class, $error);
        $this->assertEquals('Test message "array1:2:id"', $error->getMessage());
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('array1:2:id', $error->getFieldName());
        $this->assertEquals('required', $error->getMessageKey());
        $error = $result->getErrors()[1];
        $this->assertInstanceOf(IError::class, $error);
        $this->assertEquals('All "array1:1:name"', $error->getMessage());
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('array1:1:name', $error->getFieldName());
        $this->assertEquals('required', $error->getMessageKey());
    }

    /**
     * Методы добавления правил
     */
    public function testRuleMethods(): void
    {
        $this->assertFalse(Validator::hasRule(FixtureRule::class));
        $this->assertTrue(Validator::addRule(FixtureRule::class));
        $this->assertFalse(Validator::addRule(FixtureRule::class));
        $this->assertTrue(Validator::hasRule(FixtureRule::class));
        $this->assertIsString(Validator::getRuleClassByName(FixtureRule::getRuleName()));
        $this->expectException(RuleNotFound::class);
        Validator::getRuleClassByName('not_found');
    }

    /**
     * Методы добавления правил
     */
    public function testAddRuleException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Validator::addRule(static::class);
    }

    /**
     * Методы добавления правил
     */
    public function testHasRuleException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Validator::hasRule(static::class);
    }

    /**
     * Правила переданы в массиве
     */
    public function testArrayRule(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            [
                'key1' => null,
            ],
            [
                'key1' => [new Required(), new IsNull()],
            ],
            [
                'required' => 'test message {{name}}',
            ]
        );
        $result = $validation->validate();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals('required', $result->getErrors()->first()->getRuleName());
        $this->assertEquals('test message key1', $result->getErrors()->first()->getMessage());
    }
}
