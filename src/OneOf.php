<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use InvalidArgumentException;

/**
 * Одно из правил должно удовлетворять условию
 */
class OneOf extends AbstractChain
{
    /**
     * @inheritDoc
     */
    protected function setSuccess(
        ResultInterface $result,
        $success,
        ?string $ruleName = null,
        ?string $fieldName = null,
        array $messages = []
    ): void {
        if (is_null($result->isSuccess())) {
            $result->setSuccess(false);
        }
        if ($success instanceof ResultInterface) {
            $result->setSuccess($result->isSuccess() || $success->isSuccess());
            if (!$success->isSuccess()) {
                $result->addErrors($success->getErrors());
            }

            return;
        }
        $result->setSuccess($result->isSuccess() || $success);
        if (!$success) {
            if (!$ruleName) {
                throw new InvalidArgumentException('$ruleName argument is required');
            }
            foreach ($messages as $messageKey => $message) {
                $messageKey = (string) $messageKey;
                $error = new Error($ruleName, $fieldName, $messageKey, $message);
                $result->addError($error);
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function prepareResult(ResultInterface $result): ResultInterface
    {
        if ($result->isSuccess()) {
            $result->clearErrors();
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public static function create(...$rules): ChainInterface
    {
        return new OneOf(...$rules);
    }
}
