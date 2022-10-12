<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Тип загруженного файла
 */
class MimeRuleTest extends TestCase
{
    /**
     * Тип загруженного файла
     */
    public function testMime(): void
    {
        $this->assertTrue(AllOf::create()->mime('pdf')->validate([
            'type' => 'application/pdf',
        ])->isSuccess());

        $this->assertTrue(AllOf::create()->mime('pdf')->validate([
            'type' => '',
        ])->isSuccess());

        $this->assertTrue(AllOf::create()->mime('pdf')->validate('application/pdf')->isSuccess());
        $this->assertFalse(AllOf::create()->mime('pdf')->validate([
            'type' => 'application/doc',
        ])->isSuccess());
    }

    /**
     * Тип загруженного файла
     */
    public function testMimeValidator(): void
    {
        $validator = new Validator();
        $validation = $validator->make(['foo' => [
            'name' => [
                'name1.pdf',
                'name2.pdf',
                '',
            ],
            'size' => [
                1048577,
                1048577,
                0,
            ],
            'type' => [
                'application/pdf',
                'application/pdf',
                '',
            ],
            'tmp_name' => [
                '/tmp/phpWglzLC',
                '/tmp/phpWglzLC',
            ],
            'error' => [
                0,
                0,
                4,
            ],
        ],
        ], ['foo' => 'mime("pdf")']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 'application/doc']);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'mime("pdf")']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Исключение
     */
    public function testMimeException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        AllOf::create()->mime();
    }
}
