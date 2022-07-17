<?php

namespace App\Validators;

use App\Validators\BaseValidator;

class UserValidator extends BaseValidator
{
    public function validate($register = false)
    {
        $validatorArray = [
            'email'    => 'required|email',
            'password' => 'required',
        ];

        if ($register) {
            $validatorArray = array_merge($validatorArray, [
                'name'     => 'required|string',
                'username' => 'required|string'
            ]);
            $validatorArray['email'] = 'required|email||unique:users';
        }

        $this->addRules($validatorArray);

        parent::validate();
    }
}
