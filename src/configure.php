<?php

declare(strict_types=1);

use Fi1a\Validation\Rule\AlphaNumericRule;
use Fi1a\Validation\Rule\AlphaRule;
use Fi1a\Validation\Rule\ArrayRule;
use Fi1a\Validation\Rule\BetweenRule;
use Fi1a\Validation\Rule\EmailRule;
use Fi1a\Validation\Rule\MaxRule;
use Fi1a\Validation\Rule\MinRule;
use Fi1a\Validation\Rule\NullRule;
use Fi1a\Validation\Rule\NumericRule;
use Fi1a\Validation\Rule\Required;
use Fi1a\Validation\Validator;

Validator::addRule(Required::class);
Validator::addRule(NullRule::class);
Validator::addRule(NumericRule::class);
Validator::addRule(AlphaRule::class);
Validator::addRule(AlphaNumericRule::class);
Validator::addRule(EmailRule::class);
Validator::addRule(MinRule::class);
Validator::addRule(MaxRule::class);
Validator::addRule(BetweenRule::class);
Validator::addRule(ArrayRule::class);
