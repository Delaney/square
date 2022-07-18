<?php

namespace App\Validators;

use App\Validators\BaseValidator;

class PostValidator extends BaseValidator
{
    public function validate()
    {
        $validatorArray = [
            'title'       => 'required|string|max:100',
            'description' => 'required|string|max:255'
        ];

        $this->addRules($validatorArray);

        parent::validate();
    }
}
