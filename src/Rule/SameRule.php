<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ValueInterface;
use Fi1a\Validation\ValuesInterface;
use InvalidArgumentException;

/**
 * Совпадают ли значения
 */
class SameRule extends AbstractRule
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var string|null
     */
    private $fieldTitle;

    public function __construct(string $fieldName, ?string $fieldTitle = null)
    {
        if (!$fieldName) {
            throw new InvalidArgumentException('Аргумент $fieldName не может быть пустым');
        }

        $this->fieldName = $fieldName;
        $this->fieldTitle = $fieldTitle;
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        $sameValue = $this->getValue($this->fieldName);

        if (is_array($sameValue)) {
            throw new InvalidArgumentException('Значение не должно быть массивом');
        }

        if (!$value->isPresence() && !$sameValue->isPresence()) {
            return true;
        }

        assert($this->values instanceof ValuesInterface);
        $success = $this->values->asArray();
        $success = $success && $value->isPresence() && $sameValue->isPresence();
        $success = $success && $value->getValue() === $sameValue->getValue();

        if (!$success) {
            $this->addMessage(
                'Значение {{if(name)}}"{{name}}" {{endif}}должно совпадать{{if(same)}} с {{same}}{{endif}}',
                'same'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), ['same' => $this->fieldTitle]);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'same';
    }
}
