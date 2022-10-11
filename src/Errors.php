<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Collection\Collection;
use Fi1a\Collection\DataType\PathAccess;

/**
 * Ошибки
 */
class Errors extends Collection implements ErrorsInterface
{
    /**
     * @inheritDoc
     */
    public function firstOfAll(): ErrorsInterface
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
     * @inheritDoc
     */
    public function allForField(string $fieldName): ErrorsInterface
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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function allForRule(string $ruleName): ErrorsInterface
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

    /**
     * @inheritDoc
     */
    public function asArray(bool $flat = true): array
    {
        $errors = [];
        if (!$flat) {
            $errors = new PathAccess();
        }
        foreach ($this as $error) {
            assert($error instanceof ErrorInterface);
            if (!$error->getFieldName() || !$error->getMessageKey() || !$error->getMessage()) {
                continue;
            }
            if (!$flat) {
                assert($errors instanceof PathAccess);
                /**
                 * @psalm-suppress PossiblyNullOperand
                 */
                $errors->set($error->getFieldName() . ':' . $error->getMessageKey(), $error->getMessage());

                continue;
            }
            /**
             * @psalm-suppress PossiblyNullArrayOffset
             * @psalm-suppress MixedArrayAssignment
             */
            $errors[$error->getFieldName()][$error->getMessageKey()] = $error->getMessage();
        }
        if ($errors instanceof PathAccess) {
            /**
             * @var string[] $errors
             */
            $errors = $errors->getArrayCopy();
        }

        return $errors;
    }
}
