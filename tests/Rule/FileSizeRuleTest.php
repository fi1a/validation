<?php

declare(strict_types=1);

namespace Fi1a\Unit\Validation\Rule;

use Fi1a\Validation\AllOf;
use Fi1a\Validation\Validator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Размер загруженного файла
 */
class FileSizeRuleTest extends TestCase
{
    /**
     * Размер загруженного файла
     */
    public function testFileSize(): void
    {
        $this->assertTrue(AllOf::create()->fileSize('1B', '20B')->validate([
            'size' => 2,
        ])->isSuccess());

        $this->assertTrue(AllOf::create()->fileSize('1MB', '20M')->validate([
            'size' => 1048577,
        ])->isSuccess());

        $this->assertTrue(AllOf::create()->fileSize('1KB', '20K')->validate([
            'size' => 1024,
        ])->isSuccess());

        $this->assertTrue(AllOf::create()->fileSize('1GB', '20G')->validate([
            'size' => 10485770000,
        ])->isSuccess());

        $this->assertTrue(AllOf::create()->fileSize('1TB', '20T')->validate([
            'size' => 10485770000000,
        ])->isSuccess());

        $this->assertTrue(AllOf::create()->fileSize('1PB', '20P')->validate([
            'size' => 10485770000000000,
        ])->isSuccess());

        $this->assertTrue(AllOf::create()->fileSize('1PB', '20P')->validate([
            'size' => 0,
        ])->isSuccess());

        $this->assertTrue(AllOf::create()->fileSize('1MB', '20MB')->validate(1048577)->isSuccess());
        $this->assertFalse(AllOf::create()->fileSize('0', '1MB')->validate([
            'size' => 104857700000,
        ])->isSuccess());
    }

    /**
     * Размер загруженного файла
     */
    public function testFileSizeValidator(): void
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
        ], ['foo' => 'fileSize("1MB", "20MB")']);
        $this->assertTrue($validation->validate()->isSuccess());
        $validation->setValues(['foo' => 104857700000000000]);
        $this->assertFalse($validation->validate()->isSuccess());
        $validation = $validator->make([], ['foo' => 'fileSize("1MB", "20MB")']);
        $this->assertTrue($validation->validate()->isSuccess());
    }

    /**
     * Исключение
     */
    public function testMinAndMaxException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        AllOf::create()->fileSize('0', '0');
    }

    /**
     * Исключение
     */
    public function testException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        AllOf::create()->fileSize('1uu', '1mm');
    }
}
