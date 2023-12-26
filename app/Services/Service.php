<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use UnexpectedValueException;

class Service
{
    protected function getUser(): User
    {
        $user = Auth::user();

        if (!$user) {
            throw new UnexpectedValueException('silakan login terlebih dahulu!');
        }

        return $user;
    }
}
