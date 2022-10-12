<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;
use InvalidArgumentException;

/**
 * Размер загруженного файла
 */
class FileSizeRule extends AbstractFileRule
{
    /**
     * @var float
     */
    private $min;

    /**
     * @var float
     */
    private $max;

    /**
     * Конструктор
     */
    public function __construct(string $min, string $max)
    {
        $this->min = $this->getBytesSize($min);
        $this->max = $this->getBytesSize($max);

        if (!$this->min && !$this->max) {
            throw new InvalidArgumentException('Минимальный или максимальный размер файла должен быть больше нуля');
        }
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$value->isPresence()) {
            return true;
        }

        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedArrayAccess
         */
        $size = is_array($value->getValue()) && array_key_exists('size', $value->getValue())
            ? (float) $value->getValue()['size']
            : (float) $value->getValue();

        if (!$size) {
            return true;
        }

        $success = true;
        if ($this->min) {
            $success = $size >= $this->min;
        }
        if ($this->max) {
            $success = $success && $size <= $this->max;
        }

        if (!$success) {
            $this->addMessage(
                'Размер файла {{if(name)}}"{{name}}" {{endif}}'
                . 'должен быть{{if(min)}} больше {{min}} Байт{{endif}}{{if(max)}}'
                . '{{if(min)}} и{{endif}} меньше {{max}} Байт{{endif}}',
                'fileSize'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'fileSize';
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), [
            'min' => $this->min,
            'max' => $this->max,
        ]);
    }

    /**
     * Размер в байтах
     *
     * @param string|int $size
     *
     * @psalm-suppress InvalidReturnType
     */
    protected function getBytesSize($size): float
    {
        if (is_numeric($size)) {
            return (float) $size;
        }

        if (!preg_match('/^(?<number>((\d+)?\.)?\d+)(?<format>(B|K|M|G|T|P)B?)?$/i', $size, $match)) {
            throw new InvalidArgumentException('Размер имеет неизвестный формат');
        }

        $number = (float) $match['number'];
        $format = $match['format'] ?? '';

        switch (strtoupper($format)) {
            case 'KB':
            case 'K':
                return $number * 1024;
            case 'MB':
            case 'M':
                return $number * pow(1024, 2);
            case 'GB':
            case 'G':
                return $number * pow(1024, 3);
            case 'TB':
            case 'T':
                return $number * pow(1024, 4);
            case 'PB':
            case 'P':
                return $number * pow(1024, 5);
        }

        return $number;
    }
}
