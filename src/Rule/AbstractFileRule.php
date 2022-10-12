<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Value;
use Fi1a\Validation\ValueInterface;

/**
 * Абстрактный класс правила для файлов
 */
abstract class AbstractFileRule extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function beforeValidate($value)
    {
        $values = $value;
        if ($value instanceof ValueInterface && is_array($value->getValue())) {
            /**
             * @var mixed $valueItem
             */
            $valueItem = $value->getValue();
            if (
                is_array($valueItem)
                && array_key_exists('size', $valueItem)
                && is_array($valueItem['size'])
                && count($valueItem['size']) > 0
            ) {
                $values = [];

                foreach (array_keys($valueItem['size']) as $index) {
                    if (!$valueItem['size'][$index]) {
                        continue;
                    }
                    $valueInstance = new Value();

                    /** @psalm-suppress PossiblyNullOperand */
                    $valueInstance->setPath(
                        (!is_null($value->getPath()) ? $value->getPath() . ':' : '') . $index
                    );
                    /** @psalm-suppress PossiblyNullOperand */
                    $valueInstance->setWildcardPath(
                        (!is_null($value->getPath()) ? $value->getPath() . ':' : '') . ':*'
                    );
                    $valueInstance->setPresence($value->isPresence());
                    $valueInstance->setWildcardItem(true);

                    $fileValue = [
                        'size' => $valueItem['size'][$index],
                    ];

                    if (
                        array_key_exists('name', $valueItem)
                        && is_array($valueItem['name'])
                        && array_key_exists($index, $valueItem['name'])
                    ) {
                        $fileValue['name'] = (string) $valueItem['name'][$index];
                    }
                    if (
                        array_key_exists('type', $valueItem)
                        && is_array($valueItem['type'])
                        && array_key_exists($index, $valueItem['type'])
                    ) {
                        $fileValue['type'] = (string) $valueItem['type'][$index];
                    }
                    if (
                        array_key_exists('tmp_name', $valueItem)
                        && is_array($valueItem['tmp_name'])
                        && array_key_exists($index, $valueItem['tmp_name'])
                    ) {
                        $fileValue['tmp_name'] = (string) $valueItem['tmp_name'][$index];
                    }
                    if (
                        array_key_exists('error', $valueItem)
                        && is_array($valueItem['error'])
                        && array_key_exists($index, $valueItem['error'])
                    ) {
                        $fileValue['error'] = (int) $valueItem['error'][$index];
                    }

                    /** @psalm-suppress MixedArrayAccess */
                    $valueInstance->setValue($fileValue);

                    $values[] = $valueInstance;
                }
            }
        }

        return $values;
    }
}
