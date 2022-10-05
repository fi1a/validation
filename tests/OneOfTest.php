<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Unit\Validation\Fixtures\EmptyRuleName;
use Fi1a\Validation\AllOf;
use Fi1a\Validation\OneOf;
use Fi1a\Validation\Rule\IsNull;
use Fi1a\Validation\Rule\Required;
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
        $chain = new OneOf([new Required(), new IsNull()]);
        $this->assertTrue($chain->validate(1)->isSuccess());
        $this->assertTrue($chain->validate(null)->isSuccess());
        $this->assertFalse($chain->validate(false)->isSuccess());
        $this->assertTrue($chain->validate(['field' => 1], 'field')->isSuccess());
        $this->assertTrue($chain->validate(['field' => null], 'field')->isSuccess());
        $this->assertFalse($chain->validate(['field' => false], 'field')->isSuccess());
    }

    /**
     * Валидация
     */
    public function testValidateWithChain(): void
    {
        $chain = new OneOf([new AllOf([new Required()]), new AllOf([new IsNull()])]);
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
        $chain = new OneOf([new EmptyRuleName(), new EmptyRuleName()]);
        $chain->validate(false);
    }
}
