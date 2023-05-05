<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AssignedCapsule;
use App\Models\Capsule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class FacultyController extends Controller
{
    public function index(){
        $faculty = User::where('status', '1')->select('id', 'fname', 'mname', 'lname', 'email', 'contact')->get();

        return response()->json([
            'msg'=>$faculty,
        ]);
    }

    public function getUnverifiedFaculty(){
        $faculty = User::where('status', '0')->select('id', 'fname', 'mname', 'lname', 'email', 'contact')->get();

        return response()->json([
            'msg'=>$faculty,
        ]);
    }

    public function getVerifiedandUnverified(){
        $unverifiedFaculty = User::where('status', '0')->get();
        $verifiedFaculty = User::where('status', '1')->get();

        return response()->json([
            'verify'=>$verifiedFaculty,
            'notVerify'=> $unverifiedFaculty
        ]);
    }

    public function softdelete(Request $req, $id) {
        // use to softdelete an account of a user/faculty
        $user = User::find($id);
        $user->delete();
        return response()->json([
            "msg"=>"Soft Delete Successful"
        ]);
    }

    public function restore(Request $req, $id){
        //restore an account
        $user = User::withTrashed()->find($id);
        if($user){
        $user->restore();
        return response()->json([
            "msg"=>"Restored Successfully"
        ]);
        }else{
            return response()->json([
                "msg"=>"Data Not Found"
            ]);
        }
    }

    public function permadelete(Request $req, $id){
        //delete an account permanently
        $user = User::withTrashed()->find($id);
        $user->forceDelete();
        return response()->json([
            "msg"=>"Deleted Successfully"
        ]);
    }


    //assigned capsule
    public function getAssignedCapsule(){
        $assignedCapsules = AssignedCapsule::with(['user'=> function($query) {
            $query->select('id', 'fname', 'mname', 'lname', 'email');},
            'capsule', 'capsule.user'=> function($query) {
            $query->select('id', 'fname', 'mname', 'lname', 'email');}])
            ->where('faculty_id', auth()->user()->id)->get();
        return response()->json([
            'msg'=>$assignedCapsules,
        ]);
    }


    //edit profile
    public function editProfile(Request $req) {
        $user = User::find(auth()->user()->id);
        if($user){
             //add validation if needed
            $user->fname = $req->fname;
            $user->mname = $req->mname;
            $user->lname = $req->lname;
            $user->email = $req->email;
            $user->contact = $req->contact;
            $user->profilePic = $req->image;
            $user->update();

            return response()->json([
                'success'=>true,
                'msg'=>'User Updated Successfully',
                'data' => $req->all()
            ]);
        } else {
            return response()->json([
                'success'=>true,
                'msg'=>'User Not Found. Please Login Again!',
            ]);
        }
    }
}
