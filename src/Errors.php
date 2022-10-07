<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Collection\Collection;

/**
 * Ошибки
 *
 * @method IError first()
 * @method IError last()
 * @method IError delete($key)
 * @method IError put($key, $value)
 * @method IError putIfAbsent($key, $value)
 * @method IError replace($key, $value)
 * @method IError[] column(string $name)
 */
class Errors extends Collection
{
    /**
     * Возвращает первую ошибку для поля
     */
    public function firstOfAll(): Errors
    {
        $errors = new Errors(IError::class);
        $inResult = [];
        foreach ($this as $error) {
            assert($error instanceof IError);
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
        $errors = new Errors(IError::class);
        foreach ($this as $error) {
            assert($error instanceof IError);
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
    public function firstOfField(string $fieldName): ?IError
    {
        foreach ($this as $error) {
            assert($error instanceof IError);
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
        $errors = new Errors(IError::class);
        foreach ($this as $error) {
            assert($error instanceof IError);
            if (mb_strtolower($error->getRuleName()) !== mb_strtolower($ruleName)) {
                continue;
            }
            $errors[] = $error;
        }

        return $errors;
    }
}
