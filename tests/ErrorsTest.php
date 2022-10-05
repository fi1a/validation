<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation;

use Fi1a\Validation\Error;
use Fi1a\Validation\Errors;
use PHPUnit\Framework\TestCase;

/**
 * Коллекция ошибок
 */
class ErrorsTest extends TestCase
{
    /**
     * Возвращает первую ошибку для ключа
     */
    public function testFirstOfAll(): void
    {
        $errors = new Errors();
        $errors[] = new Error('required', 'field1', 'messageKey', 'message');
        $errors[] = new Error('required2', 'field1', 'messageKey2', 'message2');
        $errors[] = new Error('required', 'field2', 'messageKey', 'message');
        $this->assertCount(2, $errors->firstOfAll());
    }

    /**
     * Возвращает первую ошибку для ключа
     */
    public function testAllForField(): void
    {
        $errors = new Errors();
        $errors[] = new Error('required', 'field1', 'messageKey', 'message');
        $errors[] = new Error('required2', 'field1', 'messageKey2', 'message2');
        $errors[] = new Error('required', 'field2', 'messageKey', 'message');
        $this->assertCount(2, $errors->allForField('field1'));
        $this->assertCount(1, $errors->allForField('field2'));
    }

    /**
     * Возвращает все ошибки для конкретного правила
     */
    public function testAllForRule(): void
    {
        $errors = new Errors();
        $errors[] = new Error('required', 'field1', 'messageKey', 'message');
        $errors[] = new Error('required2', 'field1', 'messageKey2', 'message2');
        $errors[] = new Error('required', 'field2', 'messageKey', 'message');
        $this->assertCount(2, $errors->allForRule('required'));
        $this->assertCount(1, $errors->allForRule('required2'));
    }

    /**
     * Возвращает первую ошибку для конкретного поля
     */
    public function testFirstOfField(): void
    {
        $errors = new Errors();
        $errors[] = new Error('required', 'field1', 'messageKey', 'message');
        $errors[] = new Error('required2', 'field1', 'messageKey2', 'message2');
        $errors[] = new Error('required', 'field2', 'messageKey', 'message');
        $error = $errors->firstOfField('field2');
        $this->assertEquals('required', $error->getRuleName());
        $this->assertEquals('field2', $error->getFieldName());
        $this->assertEquals('messageKey', $error->getMessageKey());
        $this->assertNull($errors->firstOfField('not_exists'));
    }
}
