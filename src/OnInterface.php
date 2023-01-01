<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Объявление сценариев для правил
 */
interface OnInterface
{
    public function __construct(string $fieldName, ?ChainInterface $chain, string ...$scenario);

    /**
     * Возвращает цепочку правил
     */
    public function getChain(): ?ChainInterface;

    /**
     * Возвращает сценарии
     *
     * @return string[]
     */
    public function getScenario(): array;

    /**
     * Возвращает название поля
     */
    public function getFieldName(): string;
}
