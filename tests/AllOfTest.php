<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Unit\Validation\Fixtures\EmptyRuleName;
use Fi1a\Validation\AllOf;
use Fi1a\Validation\Rule\Required;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Все правила должны удовлетворять условию
 */
class AllOfTest extends TestCase
{
    /**
     * Правила
     */
    public function testRules(): void
    {
        $chain = new AllOf();
        $this->assertCount(0, $chain->getRules());
        $chain->setRules([]);
        $this->assertCount(0, $chain->getRules());
        $chain->setRules([new Required()]);
        $this->assertCount(1, $chain->getRules());
    }

    /**
     * Правила
     */
    public function testSetRulesException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $chain = new AllOf();
        $chain->setRules([$this]);
    }

    /**
     * Валидация
     */
    public function testValidate(): void
    {
        $chain = new AllOf([new Required(), new Required()]);
        $this->assertTrue($chain->validate(1)->isSuccess());
        $this->assertFalse($chain->validate(false)->isSuccess());
        $this->assertTrue($chain->validate(['field' => 1], 'field')->isSuccess());
        $this->assertFalse($chain->validate(['field' => false], 'field')->isSuccess());
    }

    /**
     * Методы работы с сообщениями об ошибке
     */
    public function testMessages(): void
    {
        $messages = [
            'field' => 'message',
        ];
        $chain = new AllOf([new Required(), new Required()]);
        $this->assertTrue($chain->setMessages($messages));
        $this->assertEquals($messages, $chain->getMessages());
    }

    /**
     * Исключение при пустом названии правила
     */
    public function testSetSuccessEmptyRuleException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $chain = new AllOf([new EmptyRuleName(), new EmptyRuleName()]);
        $chain->validate(false);
    }
}
