<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Обязательное значение (если задано)
 */
class RequiredIfPresenceRuleTest extends TestCase
{
    /**
     * Обязательное значение (если задано)
     */
    public function testRequiredIfPresence(): void
    {
        $this->assertTrue(AllOf::create()->requiredIfPresence()->validate(true)->isSuccess());
        $this->assertFalse(AllOf::create()->requiredIfPresence()->validate(null)->isSuccess());
        $this->assertFalse(AllOf::create()->requiredIfPresence()->validate(false)->isSuccess());
    }

    /**
     * Обязательное значение (если задано)
     */
    public function testRequiredIfPresenceValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => true], ['foo' => 'requiredIfPresence']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => null]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation->setValues([]);
        $this->assertTrue($validation->validate()->isSuccess());
    }
}
