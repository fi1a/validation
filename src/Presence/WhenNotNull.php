<?php

declare(strict_types=1);

namespace Fi1a\Validation\Presence;

/**
 * Определяет по значению null присутсвует значение или нет
 */
class WhenNotNull extends WhenNotValue
{
    public function __construct()
    {
        parent::__construct(null);
    }
}
