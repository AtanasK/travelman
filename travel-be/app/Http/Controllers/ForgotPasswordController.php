<?php

namespace App\Http\Controllers;

use App\ForgotPassword;
use App\Mail\ContactMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Class ForgotPasswordController
 *
 * Class for resetting the password
 *
 * @package App\Http\Controllers
 */
class ForgotPasswordController extends Controller
{
    /**
     * Send a mail to the given email with a link for password reset.
     * The link contains the user's id and a token for validation in its url.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $email = $request->json('email');

        $user = User::where('email', $email);

        if ($user->count()) {
            $user = $user->first();
            $token = md5(uniqid()) . md5(uniqid());

            $user->resetPasswordRequests()->create(['token' => $token]);

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

    }

    /**
     * Check if the tokens match for the specific user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = $request->json('id');
        $token = $request->json('token');

        $resetUser = User::find($id);
        if ($resetUser->count()) {
            $resetUser = $resetUser->first();

            $resetRequest = $resetUser->resetPasswordRequests()->where('token', $token);

            if ($resetRequest->count()) {
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false], 404);
            }
        } else {
            return response()->json(['status' => false], 404);
        }
    }

    /**
     * Update the specified user's password in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'token' => 'required|string',
            'password' => 'required|min:6|max:250|confirmed',
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $id = $request->json('id');
        $token = $request->json('token');
        $password = $request->json('password');

        $resetUser = User::find($id);
        if ($resetUser->count()) {
            $resetUser = $resetUser->first();

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

    }
}
