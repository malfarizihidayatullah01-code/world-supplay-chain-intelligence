<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Http\Request;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(array $credentials, Request $request): bool
    {
        if ($this->authRepository->attemptLogin($credentials)) {
            $request->session()->regenerate();
            return true;
        }

        return false;
    }

    public function logout(Request $request): void
    {
        $this->authRepository->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
