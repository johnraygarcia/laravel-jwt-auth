<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function  sendPasswordResetEmail(Request $request) {

        return $request->all();

    }
}
