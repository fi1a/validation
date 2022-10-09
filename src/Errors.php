<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Collection\Collection;

/**
 * Ошибки
 *
 * @method ErrorInterface first()
 * @method ErrorInterface last()
 * @method ErrorInterface delete($key)
 * @method ErrorInterface put($key, $value)
 * @method ErrorInterface putIfAbsent($key, $value)
 * @method ErrorInterface replace($key, $value)
 * @method ErrorInterface[] column(string $name)
 */
class Errors extends Collection
{
    /**
     * Возвращает первую ошибку для поля
     */
    public function firstOfAll(): Errors
    {
        $errors = new Errors(ErrorInterface::class);
        $inResult = [];
        foreach ($this as $error) {
            assert($error instanceof ErrorInterface);
            if (in_array($error->getFieldName(), $inResult)) {
                continue;
            }
            $inResult[] = $error->getFieldName();
            $errors[] = $error;
        }

        return $errors;
    }

    /**
     * Возвращает все ошибки для конкретного поля
     */
    public function allForField(string $fieldName): Errors
    {
        $errors = new Errors(ErrorInterface::class);
        foreach ($this as $error) {
            assert($error instanceof ErrorInterface);
            if (mb_strtolower((string) $error->getFieldName()) !== mb_strtolower($fieldName)) {
                continue;
            }
            $errors[] = $error;
        }

        return $errors;
    }

    /**
     * Возвращает первую ошибку для конкретного поля
     */
    public function firstOfField(string $fieldName): ?ErrorInterface
    {
        foreach ($this as $error) {
            assert($error instanceof ErrorInterface);
            if (mb_strtolower((string) $error->getFieldName()) !== mb_strtolower($fieldName)) {
                continue;
            }

            return $error;
        }

        return null;
    }

    /**
     * Возвращает все ошибки для конкретного правила
     */
    public function allForRule(string $ruleName): Errors
    {
        $errors = new Errors(ErrorInterface::class);
        foreach ($this as $error) {
            assert($error instanceof ErrorInterface);
            if (mb_strtolower($error->getRuleName()) !== mb_strtolower($ruleName)) {
                continue;
            }
            $errors[] = $error;
        }

        return $errors;
    }
}
