<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Admin;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Hash;

class ChangePasswordController extends Controller
{
    public function process(ChangePasswordRequest $request){

        return $this->getPasswordResetTableRow($request)->count()> 0 ? $this->changePassword($request) : $this->tokenNotFoundResponse();//->get();
    }

    private function getPasswordResetTableRow($request) {

        return DB::table('password_reset_tokens')->where(['email' =>$request->email,'token' =>$request->resetToken]);

    }

    private function tokenNotFoundResponse(){

        return response()->json(['error' =>'Token or Email is Incorrect'],Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    private function changePassword($request){

        //find email
        $user = User::whereEmail($request->email)->first();
        //update pass
        $user->update(['password' => $request->password]);
        //remove verification data from db
        $this->getPasswordResetTableRow($request)->delete();
        //reset password response
        return response()->json(['data'=>'Password Successfully Changed', 'user'=>$user],Response::HTTP_CREATED);
    }

    public function faculty_change_password(Request $req)
    {
        $validatedData = Validator::make($req->all(), [
            'old_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if($validatedData->fails()){
            return response()->json([
                'msg'=>$validatedData->messages(),
            ], 422);
        }
        $user = User::find(auth()->user()->id);
        if(Hash::check($req->old_password, $user->password)){
            $user->update([
                'password'=>$req->password
            ]);
            return response()->json([
                'msg'=>'Change Password successful',
            ]);
        }else{
            return response()->json([
                'msg'=>'Old Password is invalid.',
            ], 422);
        }
    }

    public function admin_change_password(Request $req)
    {
        $validatedData = Validator::make($req->all(), [
            'old_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if($validatedData->fails()){
            return response()->json([
                'msg'=>$validatedData->messages(),
            ], 422);
        }
        $admin = Admin::find(auth()->guard('admin')->user()->id);
        if(Hash::check($req->old_password, $admin->password)){
            $admin->update([
                'password'=>Hash::make($req->password)
            ]);
            return response()->json([
                'msg'=>'Change Password successful',
            ]);
        }else{
            return response()->json([
                'msg'=>'Old Password is invalid.',
            ], 422);
        }
    }
}
