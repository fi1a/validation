<?php

declare(strict_types=1);

use Fi1a\Validation\Rule\AlphaNumericRule;
use Fi1a\Validation\Rule\AlphaRule;
use Fi1a\Validation\Rule\ArrayRule;
use Fi1a\Validation\Rule\BetweenCountRule;
use Fi1a\Validation\Rule\BetweenLengthRule;
use Fi1a\Validation\Rule\BetweenRule;
use Fi1a\Validation\Rule\BooleanRule;
use Fi1a\Validation\Rule\EmailRule;
use Fi1a\Validation\Rule\InRule;
use Fi1a\Validation\Rule\IntegerRule;
use Fi1a\Validation\Rule\JsonRule;
use Fi1a\Validation\Rule\MaxCountRule;
use Fi1a\Validation\Rule\MaxLengthRule;
use Fi1a\Validation\Rule\MaxRule;
use Fi1a\Validation\Rule\MinCountRule;
use Fi1a\Validation\Rule\MinLengthRule;
use Fi1a\Validation\Rule\MinRule;
use Fi1a\Validation\Rule\NullRule;
use Fi1a\Validation\Rule\NumericRule;
use Fi1a\Validation\Rule\RequiredRule;
use Fi1a\Validation\Validator;

Validator::addRule(RequiredRule::class);
Validator::addRule(NullRule::class);
Validator::addRule(NumericRule::class);
Validator::addRule(AlphaRule::class);
Validator::addRule(AlphaNumericRule::class);
Validator::addRule(EmailRule::class);
Validator::addRule(MinRule::class);
Validator::addRule(MaxRule::class);
Validator::addRule(BetweenRule::class);
Validator::addRule(ArrayRule::class);
Validator::addRule(BooleanRule::class);
Validator::addRule(IntegerRule::class);
Validator::addRule(JsonRule::class);
Validator::addRule(MinLengthRule::class);
Validator::addRule(MaxLengthRule::class);
Validator::addRule(BetweenLengthRule::class);
Validator::addRule(MinCountRule::class);
Validator::addRule(MaxCountRule::class);
Validator::addRule(BetweenCountRule::class);
Validator::addRule(InRule::class);
