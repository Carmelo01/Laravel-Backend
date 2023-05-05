<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function process(ChangePasswordRequest $request){

        return $this->getPasswordResetTableRow($request)->count()> 0 ? $this->changePassword($request) : $this->tokenNotFoundResponse();//->get();
    }

    private function getPasswordResetTableRow() {

        return DB::table('password_resets')->where(['email' =>$request->email,'token' =>$request->resetToken]);

    }

    private function tokenNotFoundResponse(){

        return response->json(['error' =>'Token ad Email is Incorrect'],Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    private function changePassword($request){
        
        //find email
        $user = User::whereEmail($request->email)->first();
        //update pass
        $user->update(['password' => $request->password]);
        //remove verification data from db
        $this->getPasswordResetTableRow($request);delete();
        //reset password response
        return reponse()->json(['data'=>'Password Successfully Changed'],Response::HTTP_CREATED);
    }
}
