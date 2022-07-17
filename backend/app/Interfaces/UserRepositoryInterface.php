<?php

namespace App\Interfaces;

// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;

interface UserRepositoryInterface 
{
    public function register(Request $request);
    public function login(Request $request);
    public function getUserDetails(Request $request);
    public function getUserPosts(Request $request);
    public function validate(Array $data, Array $validators);
}