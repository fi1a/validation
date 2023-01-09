<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\ChainInterface;
use Fi1a\Validation\On;
use PHPUnit\Framework\TestCase;

/**
 * Объявление сценариев для правил
 */
class OnTest extends TestCase
{
    /**
     * Объявление сценариев для правил
     */
    public function testOn(): void
    {
        $on = new On('fieldName', AllOf::create(), 'create', 'update');
        $this->assertEquals('fieldName', $on->getFieldName());
        $this->assertEquals(['create', 'update'], $on->getScenario());
        $this->assertInstanceOf(ChainInterface::class, $on->getChain());
    }
}
