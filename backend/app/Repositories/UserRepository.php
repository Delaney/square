<?php

namespace App\Repositories;

use App\Http\Resources\Post;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\{
    Auth,
    Hash,
    Validator
};
use App\Models\User;
use App\Http\Resources\User as UserResource;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function register($request)
    {
        $user = User::create([
            'name'  => $request->input('name'),
            'email'  => $request->input('email'),
            'username'  => $request->input('username'),
            'password'  => Hash::make($request->input('password')),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'id'    => $user->id,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login($request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'id'    => $user->id,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function getUserDetails($request)
    {
        return new UserResource($request->user());
    }

    public function getUserPosts($request)
    {
        $user = $request->user();
        return Post::collection($user->posts);
    }
}
