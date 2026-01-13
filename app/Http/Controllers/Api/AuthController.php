<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request): Response
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken($user->password)->plainTextToken,
        ], Response::HTTP_CREATED);
    }

    public function logout(): void
    {
        $authUser = Auth::user();

        if ($authUser) {
            $authUser->tokens()->delete();
            $authUser->save();
        }
    }

    public function user(Request $request): Response
    {
        return response()->json([
            'user' => $request->user(),
        ], Response::HTTP_OK);
    }
}
