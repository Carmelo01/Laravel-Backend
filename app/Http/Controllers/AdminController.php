<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyUser;
use App\Mail\DeclineUser;

class AdminController extends Controller
{
    public function adminregister(Request $request){
        $admin=Admin::create([
            'fname'=>$request->fname,
            'mname'=>$request->mname,
            'lname'=>$request->lname,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        if($admin){
            return response()->json([$admin,'status'=>true]);
        }
        else{
            return response()->json(['status'=>false]);
        }
}

    public function adminlogin(Request $request){
        $credentials = request(['email', 'password']);

        if(! $token = auth()->guard('admin')->attempt($credentials, ['expires_in' => 43200])){
            return response()->json(['error' => 'Email or password is incorrect!'], 401);
        }
        return response()->json([
            'token' => $token,
            "msg"=>"Login Successful",
            'expires_in' => 43200,
            'user' => auth()->guard('admin')->user()->fname.' '.auth()->guard('admin')->user()->mname.' '.auth()->guard('admin')->user()->lname
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminme(Request $req)
    {
        return response()->json(auth()->guard('admin')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminlogout()
    {
        auth()->guard('admin')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function verifyFaculty($id)
    {
        $faculty = User::find($id);
        $faculty->status = 1;
        $faculty->update();
        Mail::to($faculty->email)->send(new VerifyUser($faculty));

        return response()->json([
            'success'=>true,
            'msg'=>'Verification Successful',
        ]);
    }

    public function declineFaculty(Request $req, $id)
    {
        $comment = $req->comment;
        if($comment == null){
            $comment = 'Please try again and make sure that you are a faculty of CICT.';
        }
        $faculty = User::find($id);
        $faculty->status = 2;
        $faculty->update();
        Mail::to($faculty->email)->send(new DeclineUser($faculty, $comment));

        return response()->json([
            'success'=>true,
            'msg'=>'Faculty Declined!',
        ]);
    }

    public function editProfile(Request $req, $id){
        $maxSize = 3072 * 3072; // 1MB as an example limit
        $decodedImageData = base64_decode($req->image);

        if (strlen($decodedImageData) > $maxSize) {
            return response()->json([
                'msg'=>'The file size should be under 1mb.',
            ], 422);
        } else{
            $admin = Admin::find($id);
            if($admin){
                $admin->fname = $req->fname;
                $admin->mname = $req->mname;
                $admin->lname = $req->lname;
                $admin->email = $req->email;
                $admin->profilePic = $req->image;
                $admin->update();

                return response()->json([
                    'msg'=>'Profile Updated Successfully',
                ], 200);
            } else {
                return response()->json([
                    'msg'=>'Profile Not Found',
                ]);
            }
        }
    }

}
