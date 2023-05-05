<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function sendEmail(Request $request){
       // return $request->all();
       if(validateEmail($request->email)){
        return $this->failedResponse();
       }

       $this->email($request->email);
       return $this->successResponse();
    }

    public function send($email){
        Mail::to($email)->send(new ResetPasswordMail);
    }

    // public function createToken($email)  // this is a function to get your request email that there are or not to send mail
    // {
    //     $oldToken = DB::table('password_resets')->where('email', $email)->first();

    //     if ($oldToken) {
    //         return $oldToken->token;
    //     }

    //     $token = Str::random(40);
    //     $this->saveToken($token, $email);
    //     return $token;
    // }


    // public function saveToken($token, $email)  // this function save new password
    // {
    //     DB::table('password_resets')->insert([
    //         'email' => $email,
    //         'token' => $token,
    //         'created_at' => Carbon::now()
    //     ]);
    // }



    public function validateEmail($email){
        return !!User::where('email', $email)->first();
    }

    public function failedResponse(){
        return response()->json([
            'error' => 'Email doesn\t found on our database'
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse(){
        return response()->json([
            'error' => 'Reset Link is send successfully to your Email, please check your inbox.'
        ], Response::HTTP_OK);
    }
}
