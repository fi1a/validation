<?php

declare(strict_types=1);

namespace Fi1a\Validation\Rule;

use Fi1a\Validation\Presence\WhenPresenceInterface;
use Fi1a\Validation\ValueInterface;
use InvalidArgumentException;

/**
 * Проверка на регулярное выражение
 */
class RegexRule extends AbstractRule
{
    /**
     * @var string
     */
    private $regex;

    /**
     * Конструктор
     */
    public function __construct(string $regex, ?WhenPresenceInterface $presence = null)
    {
        if (!$regex) {
            throw new InvalidArgumentException('Аргумент $regex не может быть пустым');
        }
        $this->regex = $regex;
        parent::__construct($presence);
    }

    /**
     * @inheritDoc
     */
    public function validate(ValueInterface $value): bool
    {
        if (!$this->presence->isPresence($value, $this->values)) {
            return true;
        }

        $success = preg_match($this->regex, (string) $value->getValue()) > 0;

        if (!$success) {
            $this->addMessage(
                'Формат {{if(name)}}"{{name}}" {{endif}}должен быть "{{regex|unescape}}"',
                'regex'
            );
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): array
    {
        return array_merge(parent::getVariables(), ['regex' => $this->regex]);
    }

    /**
     * @inheritDoc
     */
    public static function getRuleName(): string
    {
        return 'regex';
    }
}
