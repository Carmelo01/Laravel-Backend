<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommentCapsule;

class CommentCapsuleController extends Controller
{

    public function getAll($id)
    {
        $comments = CommentCapsule::where('capsule_id', $id)->get();
        return response()->json([
            'data'=>$comments,
            "msg"=>"Retrieved Comments"
        ]);
    }

    public function store(Request $req, $id) {
        // validate the incoming request data
        $validatedData = Validator::make($req->all(), [
            'comment' => 'required',
        ]);
        if($validatedData->fails()){
            return response()->json([
                'msg'=>$validatedData->messages(),
            ]);
        }
        else {
            if($req->input('role') == "admin"){
                $comment = new CommentCapsule;
                $comment->capsule_id = (int)$id;
                $comment->admin_id = $req->input('user_id');
                //can add image comment later
                $comment->comment = $req->input('comment');
                $comment->save();
            } else {
                $comment = new CommentCapsule;
                $comment->faculty_id = $req->input('user_id');
                $comment->capsule_id = (int)$id;
                //can add image comment later
                $comment->comment = $req->input('comment');
                $comment->save();
            }
            // return a response indicating success
            return response()->json([
                'message' => 'Commented successfully.',
                'data' => $comment,
            ], 201);
        }
    }
}
