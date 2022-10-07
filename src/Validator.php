<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\AST\AST;
use Fi1a\Validation\Exception\RuleNotFound;
use Fi1a\Validation\Rule\IRule;
use InvalidArgumentException;

/**
 * Валидатор
 */
class Validator implements IValidator
{
    /**
     * @var string[]
     */
    private static $ruleClasses = [];

    /**
     * @inheritDoc
     */
    public function __construct(array $messages = [])
    {
    }

    /**
     * @inheritDoc
     */
    public function make(
        $values,
        ?array $rules = null,
        array $messages = [],
        array $titles = []
    ): IValidation {
        /**
         * @var IRule[]|IChain[] $ruleInstances
         */
        $ruleInstances = [];
        if (is_array($rules)) {
            foreach ($rules as $fieldName => $rule) {
                if ($rule instanceof IRule || $rule instanceof IChain) {
                    $ruleInstances[$fieldName] = $rule;

                    continue;
                }
                if (is_array($rule)) {
                    foreach ($rule as $ruleItem) {
                        if (!($ruleItem instanceof IRule) && !($ruleItem instanceof IChain)) {
                            throw new InvalidArgumentException('Argument is not a rule');
                        }
                    }
                    $ruleInstances[$fieldName] = AllOf::create(...$rule);

                    continue;
                }
                /** @psalm-suppress RedundantConditionGivenDocblockType */
                if (is_string($rule)) {
                    $ast = new AST($rule);
                    $allOff = AllOf::create();
                    /**
                     * @var \Fi1a\Validation\AST\IRule $astRule
                     */
                    foreach ($ast->getRules() as $astRule) {
                        $ruleClass = static::getRuleClassByName($astRule->getRuleName());
                        $arguments = $astRule->getArgumentsValues();
                        /**
                         * @var IRule $ruleInstance
                         * @psalm-suppress InvalidStringClass
                         */
                        $ruleInstance = new $ruleClass(...$arguments);
                        $allOff->addRule($ruleInstance);
                    }
                    $ruleInstances[$fieldName] = $allOff;

                    continue;
                }

                throw new InvalidArgumentException('Unknown rule type');
            }
        }

        $chain = AllOf::create()
            ->setRules($ruleInstances)
            ->setMessages($messages)
            ->setTitles($titles);

        return new Validation($this, $values, $chain, $messages, $titles);
    }

    /**
     * @inheritDoc
     */
    public static function addRule(string $ruleClass): bool
    {
        if (!is_subclass_of($ruleClass, IRule::class)) {
            throw new InvalidArgumentException('The class must implement the interface ' . IRule::class);
        }
        if (static::hasRule($ruleClass)) {
            return false;
        }
        static::$ruleClasses[mb_strtolower($ruleClass::getRuleName())] = $ruleClass;

        return true;
    }

    /**
     * @inheritDoc
     */
    public static function hasRule(string $ruleClass): bool
    {
        if (!is_subclass_of($ruleClass, IRule::class)) {
            throw new InvalidArgumentException('The class must implement the interface ' . IRule::class);
        }

        return array_key_exists(mb_strtolower($ruleClass::getRuleName()), static::$ruleClasses);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleClassByName(string $ruleName): string
    {
        if (!array_key_exists(mb_strtolower($ruleName), static::$ruleClasses)) {
            throw new RuleNotFound(sprintf('Rule with name "%s" not found', $ruleName));
        }

        return static::$ruleClasses[mb_strtolower($ruleName)];
    }
}
