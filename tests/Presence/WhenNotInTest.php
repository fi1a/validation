<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Presence;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Presence\WhenNotIn;
use PHPUnit\Framework\TestCase;

/**
 * Определяет по переданным значениям присутсвует значение или нет
 */
class WhenNotInTest extends TestCase
{
    /**
     * Определяет по переданным значениям присутсвует значение или нет
     */
    public function testWhenNotIn(): void
    {
        $chain = AllOf::create()->boolean(new WhenNotIn(['empty', null]));
        $this->assertTrue($chain->validate('empty')->isSuccess());
        $this->assertTrue($chain->validate(null)->isSuccess());
        $this->assertTrue($chain->validate(true)->isSuccess());
        $this->assertTrue($chain->validate(false)->isSuccess());
        $this->assertFalse($chain->validate('not-boolean')->isSuccess());
    }
}
