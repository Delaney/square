<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\{
    Auth,
    Hash,
    Validator
};
use App\Models\User;
use App\Http\Resources\User as UserResource;

class UserRepository implements UserRepositoryInterface
{
    public function register($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'        => 'email|required|unique:users',
                'password' => 'required|string|min:8',
                'name'  => 'required|string',
                'username'   => 'required|string'
            ]
        );

        if ($validator->fails())
            return response()->json([
                'error' => 'invalid_input',
                'message' => $validator->errors()->first()
            ], 400);

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
        $validator = Validator::make(
            $request->all(),
            [
                'email'        => 'email|required',
                'password' => 'required'
            ]
        );

        if ($validator->fails())
            return response()->json([
                'error' => 'invalid_input',
                'message' => $validator->errors()->first()
            ], 400);

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
}