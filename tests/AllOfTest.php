<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Unit\Validation\Fixtures\EmptyRuleName;
use Fi1a\Validation\AllOf;
use Fi1a\Validation\IError;
use Fi1a\Validation\Rule\RequiredRule;
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
        $chain = AllOf::create();
        $this->assertCount(0, $chain->getRules());
        $chain->setRules([]);
        $this->assertCount(0, $chain->getRules());
        $chain->setRules([new RequiredRule()]);
        $this->assertCount(1, $chain->getRules());
    }

    /**
     * Правила
     */
    public function testSetRulesException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $chain = AllOf::create();
        $chain->setRules([$this]);
    }

    /**
     * Валидация
     */
    public function testValidate(): void
    {
        $chain = AllOf::create(new RequiredRule(), new RequiredRule());
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
            'required' => 'test message',
        ];
        $chain = AllOf::create(new RequiredRule(), new RequiredRule());
        $chain->setMessages($messages);
        $this->assertEquals($messages, $chain->getMessages());
    }

    /**
     * Исключение при пустом названии правила
     */
    public function testSetSuccessEmptyRuleException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $chain = AllOf::create(new EmptyRuleName(), new EmptyRuleName());
        $chain->validate(false);
    }

    /**
     * Тестирование __call и __callStatic для добавления правил валидации
     */
    public function testCall(): void
    {
        $chain = AllOf::create()->required();
        $this->assertTrue($chain->validate(1)->isSuccess());
        $this->assertFalse($chain->validate(false)->isSuccess());
        $this->assertTrue($chain->validate(['field' => 1], 'field')->isSuccess());
        $this->assertFalse($chain->validate(['field' => false], 'field')->isSuccess());

        $chain = AllOf::create();
        $chain->oneOf()->required();
        $chain->oneOf()->null();
        $this->assertFalse($chain->validate(1)->isSuccess());
        $this->assertFalse($chain->validate(false)->isSuccess());
        $this->assertFalse($chain->validate(null)->isSuccess());
    }

    /**
     * Сообщения об ошибках
     */
    public function testMessagesDeep(): void
    {
        $messages = [
            'required' => 'test message',
        ];

        $chain = AllOf::create();
        $chain->setMessages($messages);
        $chain->oneOf()->required()->null();
        $this->assertTrue($chain->validate(true)->isSuccess());
        $result = $chain->validate(false);
        $this->assertFalse($result->isSuccess());
        /**
         * @var IError $error
         */
        $error = $result->getErrors()->first();
        $this->assertEquals('test message', $error->getMessage());
    }

    /**
     * Исключение при добавлении правила
     */
    public function testAddRuleException(): void
    {
        $chain = AllOf::create();
        $this->expectException(InvalidArgumentException::class);
        $chain->addRule($this);
    }
}
