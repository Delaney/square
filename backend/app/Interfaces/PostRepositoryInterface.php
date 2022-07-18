<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PostRepositoryInterface 
{
    public function getAllPosts(Request $request);
    public function getPostById($id);
    public function create(Request $request);
    public function validate(Array $data, Array $validators);
}