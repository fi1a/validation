<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Unit\Validation\Fixtures\FixtureRuleSet;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Набор правил
 */
class RuleSetTest extends TestCase
{
    /**
     * Набор правил
     */
    public function testRuleSetScenarioCreateSuccess(): void
    {
        $validator = new Validator();
        $validation = $validator->make(new FixtureRuleSet(
            [
                'key1' => [
                    'id' => 1,
                    'name' => 'name value 1',
                    'foo' => null,
                    'bar' => false,
                ],
                'key2' => [
                    [
                        'wildcard' => null,
                    ],
                ],
            ],
            'create'
        ));
        $result = $validation->validate();
        $this->assertTrue($result->isSuccess());
    }

    /**
     * Набор правил
     */
    public function testRuleSetScenarioCreateNotSuccess(): void
    {
        $validator = new Validator();
        $validation = $validator->make(new FixtureRuleSet(
            [
                'key1' => [
                    'id' => null,
                    'name' => null,
                    'foo' => null,
                    'bar' => false,
                ],
                'key2' => [
                    [
                        'wildcard' => null,
                    ],
                ],
            ],
            'create'
        ));
        $result = $validation->validate();
        $this->assertFalse($result->isSuccess());
        $this->assertCount(2, $result->getErrors());
        $this->assertEquals(
            'Test required message ID',
            $result->getErrors()->firstOfAll()->first()->getMessage()
        );
    }

    /**
     * Набор правил
     */
    public function testRuleSetScenarioUpdateSuccess(): void
    {
        $validator = new Validator();
        $validation = $validator->make(new FixtureRuleSet(
            [
                'key1' => [
                    'bar' => null,
                ],
            ],
            'update'
        ));
        $result = $validation->validate();
        $this->assertTrue($result->isSuccess());
    }

    /**
     * Набор правил
     */
    public function testRuleSetScenarioUpdateNotSuccess(): void
    {
        $validator = new Validator();
        $validation = $validator->make(new FixtureRuleSet(
            [
                'key1' => [
                    'bar' => false,
                ],
            ],
            'update'
        ));
        $result = $validation->validate();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals(
            'Test required message key1:bar',
            $result->getErrors()->firstOfAll()->first()->getMessage()
        );
    }

    /**
     * Набор правил
     */
    public function testRuleSetSuccess(): void
    {
        $validator = new Validator();
        $validation = $validator->make(new FixtureRuleSet(
            [
                'key1' => [
                    'foo' => null,
                ],
            ]
        ));
        $result = $validation->validate();
        $this->assertTrue($result->isSuccess());
    }

    /**
     * Набор правил
     */
    public function testRuleSetNotSuccess(): void
    {
        $validator = new Validator();
        $validation = $validator->make(new FixtureRuleSet(
            [
                'key1' => [
                    'foo' => false,
                ],
            ]
        ));
        $result = $validation->validate();
        $this->assertFalse($result->isSuccess());
        $this->assertEquals(
            'Test required message key1:foo',
            $result->getErrors()->firstOfAll()->first()->getMessage()
        );
    }
}
