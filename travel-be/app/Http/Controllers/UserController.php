<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $user->first_name = $request->json('first_name');
        $user->last_name = $request->json('last_name');
        $user->email = $request->json('email');
        if ($user->save())
            return response()->json(['success' => true], 200);
        return response()->json(['success' => false], 500);
    }

    public function create(Request $request)
    {
        $success = User::create([
            'first_name' => $request->json('firstName'),
            'last_name' => $request->json('lastName'),
            'email' => $request->json('email'),
            'password' => bcrypt($request->json('password')),
        ]);

        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);

        if ($success) {
            return response()->json([
                'success' => true,
                'access_token' => $token,
                'expires_in' => auth()->factory()->getTTL() * 60
            ], 201);
        }

        return response()->json(['success' => false], 500);
    }
}
