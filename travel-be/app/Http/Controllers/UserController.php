<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if ($request->json('change') == 1) {
            if (Hash::check($request->json('old_password'), $user->password)) {
                $user->password = bcrypt($request->json('new_password'));
            } else {
                return response()->json(['status' => 'wrong pass'], 500);
            }
        } else {
            $user->first_name = $request->json('first_name');
            $user->last_name = $request->json('last_name');
            $user->email = $request->json('email');
        }

        if ($user->save())
            return response()->json(['success' => true], 200);
        return response()->json(['success' => false], 500);
    }

    public function create(Request $request)
    {
        $newUser = User::create([
            'first_name' => $request->json('firstName'),
            'last_name' => $request->json('lastName'),
            'email' => $request->json('email'),
            'password' => bcrypt($request->json('password')),
        ]);

        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);

        if ($newUser) {

            $data = [
                'subject' => "Thanks for joining travelman, {$newUser->first_name}",
                'heading' => "Welcome, and thanks for joining us!",
                'message' => "Hello <strong>{$newUser->first_name}</strong>!<br>Thanks for joining travelman. Enjoy your stay!",
            ];
            Mail::to($newUser->email)->send(new ContactMail($data));


            return response()->json([
                'success' => true,
                'access_token' => $token,
                'expires_in' => auth()->factory()->getTTL() * 60
            ], 201);
        }

        return response()->json(['success' => false], 500);
    }
}
