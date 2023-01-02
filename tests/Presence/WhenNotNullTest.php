<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Presence;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Presence\WhenNotNull;
use PHPUnit\Framework\TestCase;

/**
 * Определяет по значению null присутсвует значение или нет
 */
class WhenNotNullTest extends TestCase
{
    /**
     * Определяет по значению null присутсвует значение или нет
     */
    public function testNotWhenNull(): void
    {
        $chain = AllOf::create()->boolean(new WhenNotNull());
        $this->assertTrue($chain->validate(null)->isSuccess());
        $this->assertTrue($chain->validate(true)->isSuccess());
        $this->assertTrue($chain->validate(false)->isSuccess());
        $this->assertFalse($chain->validate('not-boolean')->isSuccess());
    }
}
