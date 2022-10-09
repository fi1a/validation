<?php

declare(strict_types=1);

use Fi1a\Validation\Rule\AlphaNumericRule;
use Fi1a\Validation\Rule\AlphaRule;
use Fi1a\Validation\Rule\ArrayRule;
use Fi1a\Validation\Rule\BetweenRule;
use Fi1a\Validation\Rule\Email;
use Fi1a\Validation\Rule\Max;
use Fi1a\Validation\Rule\Min;
use Fi1a\Validation\Rule\NullRule;
use Fi1a\Validation\Rule\Numeric;
use Fi1a\Validation\Rule\Required;
use Fi1a\Validation\Validator;

Validator::addRule(Required::class);
Validator::addRule(NullRule::class);
Validator::addRule(Numeric::class);
Validator::addRule(AlphaRule::class);
Validator::addRule(AlphaNumericRule::class);
Validator::addRule(Email::class);
Validator::addRule(Min::class);
Validator::addRule(Max::class);
Validator::addRule(BetweenRule::class);
Validator::addRule(ArrayRule::class);
