<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Collection\Collection;

/**
 * Результирующие значения
 */
class ResultValues extends Collection implements ResultValuesInterface
{
    /**
     * @inheritDoc
     */
    public function __construct(?array $data = null)
    {
        parent::__construct(ValueInterface::class, $data);
    }

    /**
     * @inheritDoc
     */
    public function getValid(): ResultValuesInterface
    {
        $values = new ResultValues();
        foreach ($this as $value) {
            assert($value instanceof ValueInterface);
            if ($value->isValid()) {
                $values->add($value);
            }
        }

        return $values;
    }

    /**
     * @inheritDoc
     */
    public function getInvalid(): ResultValuesInterface
    {
        $values = new ResultValues();
        foreach ($this as $value) {
            assert($value instanceof ValueInterface);
            if (!$value->isValid()) {
                $values->add($value);
            }
        }

        return $values;
    }
}
