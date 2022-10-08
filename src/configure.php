<?php

declare(strict_types=1);

use Fi1a\Validation\Rule\Alpha;
use Fi1a\Validation\Rule\IsNull;
use Fi1a\Validation\Rule\Numeric;
use Fi1a\Validation\Rule\Required;
use Fi1a\Validation\Validator;

Validator::addRule(Required::class);
Validator::addRule(IsNull::class);
Validator::addRule(Numeric::class);
Validator::addRule(Alpha::class);
