<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\UserRepositoryInterface;
use App\Validators\UserValidator;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $this->userRepository
            ->validate($request->all(), [
                UserValidator::class
            ], true);

        return $this->userRepository->register($request);
    }

    public function login(Request $request)
    {
        $this->userRepository
            ->validate($request->all(), [
                UserValidator::class
            ]);

        return $this->userRepository->login($request);
    }
}
