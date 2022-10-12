<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use InvalidArgumentException;

/**
 * Все правила должны удовлетворять условию
 */
class AllOf extends AbstractChain
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
        if ($success instanceof ResultInterface) {
            $result->setSuccess($result->isSuccess() && $success->isSuccess());
            if (!$success->isSuccess()) {
                $result->addErrors($success->getErrors());
            }

            return;
        }
        $result->setSuccess($result->isSuccess() && $success);
        if (!$success) {
            if (!$ruleName) {
                throw new InvalidArgumentException('$ruleName argument is required');
            }
            foreach ($messages as $messageKey => $message) {
                $messageKey = (string) $messageKey;
                $result->addError(new Error($ruleName, $fieldName, $messageKey, $message));
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function beforeValidate(ResultInterface $result): ResultInterface
    {
        $result = parent::beforeValidate($result);
        $result->setSuccess(true);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public static function create(...$rules): ChainInterface
    {
        return new AllOf(...$rules);
    }
}
