<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Validation\Value;
use PHPUnit\Framework\TestCase;

/**
 * Значение
 */
class ValueTest extends TestCase
{
    /**
     * Значение
     */
    public function testValue(): void
    {
        $value = new Value();
        $this->assertTrue($value->setValue(1));
        $this->assertEquals(1, $value->getValue());
    }

    /**
     * Путь
     */
    public function testPath(): void
    {
        $value = new Value();
        $this->assertTrue($value->setPath('key1:key2'));
        $this->assertEquals('key1:key2', $value->getPath());
    }

    /**
     * Путь с символом замены
     */
    public function testWildcardPath(): void
    {
        $value = new Value();
        $this->assertTrue($value->setWildcardPath('key1:key2'));
        $this->assertEquals('key1:key2', $value->getWildcardPath());
    }

    /**
     * Является значение массивом
     */
    public function testWildcard(): void
    {
        $value = new Value();
        $this->assertIsBool($value->setWildcard(true));
        $this->assertTrue($value->isWildcard());
    }

    /**
     * Наличие значения в массиве значений
     */
    public function testPresence(): void
    {
        $value = new Value();
        $this->assertIsBool($value->setPresence(true));
        $this->assertTrue($value->isPresence());
    }
}
