<?php

namespace App\Http\Controllers;

use App\ForgotPassword;
use App\User;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request)
    {
        $email = $request->json('email');

        if ($email) {
            $user = User::where('email', $email);

            if ($user->count()) {
                $token = md5(uniqid()) . md5(uniqid());

                $saveToken = new ForgotPassword();
                $saveToken->token = $token;
                $user->resetPasswordRequests()->save($saveToken);

                $data = [
                    'subject' => "Reset password for your account {$user->first_name}",
                    'heading' => "Trouble signing in?",
                    'message' => `Hello <strong>{$user->first_name}</strong>!<br>
                    There is a request to change your password. Resetting your password is easy. Just press the
                    button below and follow the instructions. We'll have you up and running in no time. If you did not
                    make this request, just ignore this email.`,
                    'reset' => [
                        'id' => $user->id,
                        'token' => $token
                    ]
                ];
                Mail::to($user->email)->send(new ContactMail($data));

                return response()->json(['success' => true]);

            } else {
                return response()->json(
                    ['error' => 'No user with that email found'],
                    400
                );
            }
        } else {
            return response()->json(
                ['error' => 'No email provided'],
                400
            );
        }
    }

    public function check(Request $request)
    {
        $id = $request->json('id');
        $token = $request->json('token');

        if ($id && $token) {
            $resetUser = User::find($id);
            if ($resetUser->count()) {
                $resetUser = $resetUser->get();

                $resetRequest = $resetUser->resetPasswordRequests()->where('token', $token);

                if ($resetRequest->count()) {
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => false], 404);
                }
            } else {
                return response()->json(['status' => false], 404);
            }
        } else {
            return response()->json(
                ['error' => 'Bad input'],
                400
            );
        }
    }

    public function new(Request $request)
    {
        $id = $request->json('id');
        $token = $request->json('token');
        $password = $request->json('password');

        if ($id && $token && $password) {
            $resetUser = User::find($id);
            if ($resetUser->count()) {
                $resetUser = $resetUser->get();

                $resetRequest = $resetUser->resetPasswordRequests()->where('token', $token);

                if ($resetRequest->count()) {
                    $resetUser->password = bcrypt($password);
                    if ($resetUser->save()) {
                        $resetRequest->delete();
                        return response()->json(['status' => true]);
                    } else {
                        return response()->json(['status' => 'Failed to save'], 500);
                    }
                } else {
                    return response()->json(['status' => false], 404);
                }
            } else {
                return response()->json(['status' => false], 404);
            }
        } else {
            return response()->json(
                ['error' => 'Bad input'],
                400
            );
        }
    }
}
