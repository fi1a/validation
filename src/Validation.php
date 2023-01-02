<?php

declare(strict_types=1);

namespace Fi1a\Validation;

use Fi1a\Validation\Presence\WhenPresenceInterface;

/**
 * Класс проверки значений
 */
class Validation implements ValidationInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var mixed
     */
    private $values;

    /**
     * @var ChainInterface
     */
    private $chain;

    /**
     * @var string[]
     */
    private $messages = [];

    /**
     * @var string[]|null[]
     */
    private $titles = [];

    /**
     * @inheritDoc
     */
    public function __construct(
        ValidatorInterface $validator,
        $values,
        ChainInterface $chain,
        array $messages,
        array $titles
    ) {
        $this->validator = $validator;
        $this->setValues($values);
        $this->chain = $chain;
        $this->setMessages($messages);
        $this->setTitles($titles);
    }

    /**
     * @inheritDoc
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @inheritDoc
     */
    public function validate(): ResultInterface
    {
        return $this->chain->validate($this->values, false);
    }

    /**
     * @inheritDoc
     */
    public function setMessages(array $messages): bool
    {
        $this->messages = $messages;
        $this->chain->setMessages($messages);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string $key, ?string $message): bool
    {
        $messages = $this->getMessages();
        if (!$message) {
            unset($messages[$key]);
        } else {
            $messages[$key] = $message;
        }
        $this->setMessages($messages);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @inheritDoc
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @inheritDoc
     */
    public function setValues($values): bool
    {
        $this->values = $values;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function setTitle(string $fieldName, ?string $title): bool
    {
        $titles = $this->getTitles();
        $titles[$fieldName] = $title;
        $this->setTitles($titles);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function setTitles(array $titles): bool
    {
        $this->titles = $titles;
        $this->chain->setTitles($titles);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @inheritDoc
     */
    public function setPresence(?WhenPresenceInterface $presence): bool
    {
        return $this->chain->setPresence($presence);
    }

    /**
     * @inheritDoc
     */
    public function getPresence(): ?WhenPresenceInterface
    {
        return $this->chain->getPresence();
    }
}
