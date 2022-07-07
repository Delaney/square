<?php

namespace App\Interfaces;

use Illuminate\Support\Facades\Request;

interface PostRepositoryInterface 
{
    public function getAllPosts(Request $request);
    public function getPostById($id);
    public function create(Request $request);
}