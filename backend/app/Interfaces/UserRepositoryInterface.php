<?php

namespace App\Interfaces;

use Illuminate\Support\Facades\Request;

interface UserRepositoryInterface 
{
    public function register(Request $request);
    public function login(Request $request);
    public function getUserDetails(Request $request);
    public function getUserPosts(Request $request);
}