<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\ChainInterface;
use Fi1a\Validation\ErrorInterface;
use Fi1a\Validation\Presence\WhenNotNull;
use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\Validator;
use Fi1a\Validation\ValueInterface;

/**
 * Вложенные правила
 */
class GenericRule extends AbstractRule
{
    /**
     * @var string[]|RuleInterface[][]|RuleInterface[]|ChainInterface[]
     */
    protected $validationRules;

    /**
     * @var string[]
     */
    protected $validationMessages;

    /**
     * @var string[]
     */
    protected $validationTitles;

    /**
     * Конструктор
     *
     * @param string[]|RuleInterface[][]|RuleInterface[]|ChainInterface[] $rules
     * @param string[] $messages
     * @param string[] $titles
     */
    public function __construct(
        array $rules,
        array $messages = [],
        array $titles = [],
        ?WhenPresenceInterface $presence = null
    ) {
        $this->validationRules = $rules;
        $this->validationMessages = $messages;
        $this->validationTitles = $titles;
        if ($presence === null) {
            $presence = new WhenNotNull();
        }
        parent::__construct($presence);
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->getPresence()->isPresence($value, $this->values)) {
            return true;
        }

        $validator = new Validator();
        $validation = $validator->make(
            $value->getValue(),
            $this->validationRules,
            $this->validationMessages,
            $this->validationTitles
        );
        $result = $validation->validate();

        if ($result->isSuccess() === false) {
            /** @var ErrorInterface $error */
            foreach ($result->getErrors() as $error) {
                $errorMessage = $error->getMessage();
                $errorKey = $error->getMessageKey();
                if ($errorMessage && $errorKey) {
                    $this->addMessage($errorMessage, $errorKey);
                }
            }
        }

        return $result->isSuccess() === true;
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'generic';
    }
}
