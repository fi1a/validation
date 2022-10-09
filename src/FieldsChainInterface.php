<?php

declare(strict_types=1);

namespace Fi1a\Validation;

/**
 * Интерфейс цепочки объявления правил
 */
interface FieldsChainInterface
{
    /**
     * Цепочка для определнного сценария
     */
    public function on(?string $scenario): FieldsChainInterface;

    /**
     * Возвращает сценарий
     */
    public function getScenario(): ?string;

    /**
     * Все правила должны удовлетворять условию
     */
    public function allOf(): AllOf;

    /**
     * Одно из правил должно удовлетворять условию
     */
    public function oneOf(): OneOf;

    /**
     * Возвращает цепочку правил
     */
    public function getChain(): ?ChainInterface;
}
