<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Unit\Validation\Fixtures\EmptyRuleName;
use Fi1a\Validation\AllOf;
use Fi1a\Validation\OneOf;
use Fi1a\Validation\Rule\NullRule;
use Fi1a\Validation\Rule\RequiredRule;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Одно из правил должно удовлетворять условию
 */
class OneOfTest extends TestCase
{
    /**
     * Валидация
     */
    public function testValidate(): void
    {
        $chain = OneOf::create(new RequiredRule(), new NullRule());

        $result = $chain->validate(1);
        $this->assertTrue($result->isSuccess());
        $this->assertCount(0, $result->getErrors());

        $result = $chain->validate(null);
        $this->assertTrue($result->isSuccess());
        $this->assertCount(0, $result->getErrors());

        $result = $chain->validate(false);
        $this->assertFalse($result->isSuccess());
        $this->assertCount(2, $result->getErrors());
        $this->assertEquals(
            'Значение является обязательным',
            $result->getErrors()->get(0)->getMessage()
        );
        $this->assertEquals(
            'Значение не является пустым',
            $result->getErrors()->get(1)->getMessage()
        );

        $result = $chain->validate(['field' => 1], 'field');
        $this->assertTrue($result->isSuccess());
        $this->assertCount(0, $result->getErrors());

        $result = $chain->validate(['field' => null], 'field');
        $this->assertTrue($result->isSuccess());
        $this->assertCount(0, $result->getErrors());

        $result = $chain->validate(['field' => false], 'field');
        $this->assertFalse($result->isSuccess());
        $this->assertCount(2, $result->getErrors());
        $this->assertEquals(
            'Значение "field" является обязательным',
            $result->getErrors()->get(0)->getMessage()
        );
        $this->assertEquals(
            'Значение "field" не является пустым',
            $result->getErrors()->get(1)->getMessage()
        );
    }

    /**
     * Валидация
     */
    public function testValidateWithChain(): void
    {
        $chain = OneOf::create(AllOf::create(new RequiredRule()), AllOf::create(new NullRule()));
        $this->assertTrue($chain->validate(1)->isSuccess());
        $this->assertTrue($chain->validate(null)->isSuccess());
        $this->assertFalse($chain->validate(false)->isSuccess());
        $this->assertTrue($chain->validate(['field' => 1], 'field')->isSuccess());
        $this->assertTrue($chain->validate(['field' => null], 'field')->isSuccess());
        $this->assertFalse($chain->validate(['field' => false], 'field')->isSuccess());
    }

    /**
     * Исключение при пустом названии правила
     */
    public function testSetSuccessEmptyRuleException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $chain = OneOf::create(new EmptyRuleName(), new EmptyRuleName());
        $chain->validate(false);
    }

    /**
     * Тестирование __call и __callStatic для добавления правил валидации
     */
    public function testCall(): void
    {
        $chain = OneOf::create();
        $chain->allOf()->required();
        $chain->allOf()->null();
        $this->assertTrue($chain->validate(1)->isSuccess());
        $this->assertTrue($chain->validate(null)->isSuccess());
        $this->assertFalse($chain->validate(false)->isSuccess());
        $this->assertTrue($chain->validate(['field' => 1], 'field')->isSuccess());
        $this->assertTrue($chain->validate(['field' => null], 'field')->isSuccess());
        $this->assertFalse($chain->validate(['field' => false], 'field')->isSuccess());
    }

    /**
     * Цепочка считается выполненной, если не представлены значения
     */
    public function testNoPresenceResult(): void
    {
        $chain = OneOf::create()->array()->null();
        $result = $chain->validate([], 'foo:*:bar');
        $this->assertTrue($result->isSuccess());
        $this->assertCount(0, $result->getErrors());
    }
}
