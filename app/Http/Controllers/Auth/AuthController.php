<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login()
    {
        $credentials = request(['username', 'password']);
        $emailCredentials = [
            'email'    => $credentials['username'],
            'password' => $credentials['password'],
        ];

        $emailLoginToken = auth()->attempt($emailCredentials);
        $token = $emailLoginToken ?: auth()->attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $token = auth()->refresh();
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $roles = Role::all();

        return response()->json([
            'roles' => $roles,
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 1000 * 60,
        ]);
    }
}
