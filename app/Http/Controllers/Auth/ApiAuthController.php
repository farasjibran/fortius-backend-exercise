<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;

class ApiAuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Register a new user
     *
     * @param   RegisterRequest  $request
     *
     * @return  JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request);

        return $this->ok(['user' => $user, 'token' => $this->service->responseToken($user)]);
    }

    /**
     * Login a user
     *
     * @param   LoginRequest  $request
     *
     * @return  JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = $this->service->login($request);

        return $this->ok(['user' => $user, 'token' => $this->service->responseToken($user)]);
    }

    /**
     * Logout a user
     *
     * @return  JsonResponse
     */
    public function logout()
    {
        return $this->ok($this->service->logout());
    }
}
