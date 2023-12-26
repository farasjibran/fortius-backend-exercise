<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use UnexpectedValueException;

class AuthService extends Service
{
  public const TOKEN_KEY = 'LARAVEL_FORTIUS_EXERCISE';

  public function responseToken($user)
  {
    return $user->createToken(self::TOKEN_KEY)->accessToken;
  }

  public function login(LoginRequest $request)
  {
    $user = User::where('email', $request->email)->first();

    if (!$user) {
      throw new UnauthorizedException('pengguna belum terdaftar');
    }

    $auth = Auth::guard('api');

    if ($auth->check()) {
      $auth->user()->token()->revoke();
    }

    if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
      throw new UnauthorizedException('password salah');
    }

    return $user;
  }

  public function register(RegisterRequest $request)
  {
    $createdUser = User::where('email', $request->email)->first();

    if ($createdUser) {
      throw new UnexpectedValueException('email sudah terdaftar');
    }

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password),
      'role' => Role::USER
    ]);

    $user->assignRole(Role::USER);

    if (!$user) {
      throw new UnexpectedValueException('gagal melakukan registrasi');
    }

    return $user;
  }

  public function logout()
  {
    Auth::user()->token()->revoke();

    return null;
  }
}
