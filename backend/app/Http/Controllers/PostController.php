<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\PostRepositoryInterface;
use App\Validators\PostValidator;

class PostController extends Controller
{

    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        return $this->postRepository->getAllPosts($request);
    }

    public function show(Request $request, $id)
    {
        return $this->postRepository->getPostById($id);
    }

    public function create(Request $request)
    {
        $this->postRepository
            ->validate($request->all(), [
                PostValidator::class
            ]);

        return $this->postRepository->create($request);
    }
}
