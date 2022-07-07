<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use WithFaker, CreatesApplication;

    public static function getUserData($password = 'wordpass101', $email = false)
    {
        $faker = \Faker\Factory::create();
        $name = $faker->unique()->userName();

        return [
            'email'    => $email ? $email : $faker->email,
            'password' => $password ?? 'wordpass101',
            'name'     => $name,
            'username' => $name
        ];
    }

    public static function getPostData()
    {
        $faker = \Faker\Factory::create();

        return [
            'title' => $faker->sentence($faker->numberBetween(2, 7)),
            'description' => $faker->realText(100)
        ];
    }
}
