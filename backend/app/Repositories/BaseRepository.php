<?php

namespace App\Repositories;

class BaseRepository
{
    public function validate($data, $validators, $register = false)
    {
        foreach ($validators as $validator) {
            $validator::make($data)->validate($register);
        }
    }
}