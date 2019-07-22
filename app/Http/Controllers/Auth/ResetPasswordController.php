<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ChangePasswordRequest;

class ResetPasswordController extends Controller
{
    public function  sendPasswordResetEmail(Request $request) {

        $email = $request->email;
        if ( !$this->validateEmail($email)) {
            return $this->failedResponse();
        } else {

            $this->send($email);
            return $this->successResponse();
        }
    }

    public function validateEmail($email) {
        return !!User::where('email', $email)->first();
    }

    public function send($email) {

        $token = $this->createToken($email);
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

    public function createToken($email) {

        $oldToken = DB::table('password_resets')->where([
            'email' => $email
        ])->first('token');

        if ($oldToken) {
            return $oldToken->token;
        }

        $token = str_random(60);
        return $this->saveToken($token, $email);
    }

    public function saveToken($token, $email) {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        return $token;
    }

    public function successResponse() {
        return response()->json([
            'data' => 'Email sent. Please check your inbox'
        ], Response::HTTP_OK);
    }

    public function failedResponse() {
        return response()->json([
            'data' => 'Email does\'t exists'
        ], Response::HTTP_NOT_FOUND);
    }

    public function changePassword(ChangePasswordRequest $request) {

        if (!$this->checkTokenIsValid($request)) {
            return $this->tokenNotFoundResponse();
        } else {

            $user = User::whereEmail($request->email)->first();
            dd($user);


            return response()->json([
                'data' => 'Success! Password is updated.'
            ], Response::HTTP_OK);
        }

    }

    private function checkTokenIsValid(ChangePasswordRequest $request) {
        return !!DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token
        ]);
    }

    public function tokenNotFoundResponse() {
        return response()->json([
            'data' => 'Invalid token'
        ], Response::HTTP_FORBIDDEN);
    }
}
