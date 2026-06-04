<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

public function login(Request $request)
{
    $request->validate(['email' => 'required|email', 'password' => 'required']);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user = Auth::user();

    if (!$user->hasRole('user')) {
        Auth::logout();
        return response()->json(['message' => 'Not a student account'], 403);
    }

    $token = $user->createToken('student-token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user]);
}
}
