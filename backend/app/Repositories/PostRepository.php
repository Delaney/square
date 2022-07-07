<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\{
    Auth,
    Hash,
    Validator
};
use App\Models\Post;
use App\Http\Resources\Post as PostResource;

class PostRepository implements PostRepositoryInterface
{
    public function getAllPosts($request)
    {
        $per_page = $request->input('per_page', 10);
        $order = $request->input('order_by', 'desc');
        $query = $request->input("query");

        $builder = Post::query($query)->orderBy('publication_date', $order);

        return PostResource::collection($builder->paginate($per_page));
    }

    public function getPostById($id)
    {
        return new PostResource(Post::findOrFail($id));
    }

    public function create($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title'        => 'required|string|max:100',
                'description' => 'required|string|max:255'
            ]
        );

        if ($validator->fails())
            return response()->json([
                'error' => 'invalid_input',
                'message' => $validator->errors()->first()
            ], 400);

        $user = $request->user();
        $request->merge([
            'user_id' => $user->id
        ]);

        return new PostResource(Post::create($request->all()));
    }
}
