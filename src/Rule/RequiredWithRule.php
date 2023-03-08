<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenNotNull;
use Fi1a\Validation\ValueInterface;
use InvalidArgumentException;

/**
 * Обязательное значение, если есть значения в полях
 */
class RequiredWithRule extends RequiredRule
{
    /**
     * @var string[]
     */
    private $fieldNames;

    public function __construct(string ...$fieldNames)
    {
        if (!count($fieldNames)) {
            throw new InvalidArgumentException('Аргумент $fieldNames не может быть пустым');
        }

        $this->fieldNames = $fieldNames;
        parent::__construct(new WhenNotNull());
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        foreach ($this->fieldNames as $fieldName) {
            $fieldValue = $this->getValue($fieldName);

            if (is_array($fieldValue)) {
                continue;
            }

            if (!$this->getPresence()->isPresence($fieldValue, $this->values)) {
                return true;
            }
        }

        return parent::validate($value);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'requiredWith';
    }
}
