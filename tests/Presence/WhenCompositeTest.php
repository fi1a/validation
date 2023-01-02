<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Presence;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Presence\WhenComposite;
use Fi1a\Validation\Presence\WhenNotNull;
use Fi1a\Validation\Presence\WhenNotValue;
use PHPUnit\Framework\TestCase;

/**
 *  Используется как составной из других классов проверки присутсвия значения
 */
class WhenCompositeTest extends TestCase
{
    /**
     * Определяет по значению null присутсвует значение или нет
     */
    public function testWhenComposite(): void
    {
        $chain = AllOf::create()->boolean(
            new WhenComposite(new WhenNotNull(), new WhenNotValue('empty'))
        );
        $this->assertTrue($chain->validate('empty')->isSuccess());
        $this->assertTrue($chain->validate(null)->isSuccess());
        $this->assertTrue($chain->validate(true)->isSuccess());
        $this->assertTrue($chain->validate(false)->isSuccess());
        $this->assertFalse($chain->validate('not-boolean')->isSuccess());
    }
}
