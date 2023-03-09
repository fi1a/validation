<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Вложенные правила
 */
class GenericRuleTest extends TestCase
{
    /**
     * Вложенные правила
     */
    public function testGeneric(): void
    {
        $this->assertTrue(
            AllOf::create()
                ->generic([
                    'bar' => 'required',
                ])
                ->validate(
                    [
                        'columns' => [
                            [
                                'foo' => null,
                            ],
                            [
                                'foo' => [
                                    'bar' => 'baz',
                                ],
                            ],
                        ],
                    ],
                    'columns:*:foo'
                )
                ->isSuccess()
        );

        $this->assertFalse(
            AllOf::create()
                ->generic([
                    'bar' => 'required',
                ])
                ->validate(
                    [
                        'columns' => [
                            [
                                'foo' => [],
                            ],
                            [
                                'foo' => [
                                    'bar' => 'baz',
                                ],
                            ],
                        ],
                    ],
                    'columns:*:foo'
                )
                ->isSuccess()
        );
    }

    /**
     * Вложенные правила
     */
    public function testGenericValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(
            [
                'columns' => [
                    [
                        'foo' => null,
                    ],
                    [
                        'foo' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
            ],
            [
                'columns' => AllOf::create()->array(),
                'columns:*:foo' => AllOf::create()->generic(['bar' => 'required']),
            ]
        );
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues([
            'columns' => [
                [
                    'foo' => [],
                ],
                [
                    'foo' => [
                        'bar' => 'baz',
                    ],
                ],
            ],
        ]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation->setValues([]);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
