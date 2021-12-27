<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use \Prettus\Validator\LaravelValidator;

/**
 * Class BaseValidator.
 *
 * @package namespace App\Validators;
 */
class BaseValidator extends LaravelValidator
{
    public function __construct(Factory $validator)
    {
        parent::__construct($validator);
    }
}
