<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Presence;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Presence\WhenNotValue;
use PHPUnit\Framework\TestCase;

/**
 * Определяет по переданному значению присутсвует значение или нет
 */
class WhenNotValueTest extends TestCase
{
    /**
     * Определяет по переданному значению присутсвует значение или нет
     */
    public function testWhenNotValue(): void
    {
        $chain = AllOf::create()->boolean(new WhenNotValue('empty'));
        $this->assertTrue($chain->validate('empty')->isSuccess());
        $this->assertTrue($chain->validate(true)->isSuccess());
        $this->assertTrue($chain->validate(false)->isSuccess());
        $this->assertFalse($chain->validate('not-boolean')->isSuccess());
    }
}
