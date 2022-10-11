<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Collection\ICollection;

/**
 * Интерфейс результирующих значений
 *
 * @method ValueInterface first()
 * @method ValueInterface last()
 * @method ValueInterface delete($key)
 * @method ValueInterface put($key, $value)
 * @method ValueInterface putIfAbsent($key, $value)
 * @method ValueInterface replace($key, $value)
 * @method ValueInterface[] column(string $name)
 */
interface ResultValuesInterface extends ICollection
{
    /**
     * Возвращает значения успешно прошедшие проверку
     */
    public function getValid(): ResultValuesInterface;

    /**
     * Возвращает значения не прошедшие проверку
     */
    public function getInvalid(): ResultValuesInterface;
}
