<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

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
}
