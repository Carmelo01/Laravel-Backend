<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GradeRubric;
use App\Models\AssignedCapsule;
use App\Models\CommentCapsule;
use App\Models\User;

class GradeRubricController extends Controller
{
    public function getGradedRubrics(Request $req){
        $verifyReviewer = GradeRubric::with('rubric', 'rubric.category')->where('faculty_id', $req->reviewer_id)->where('capsule_id', $req->capsule_id)->get();
        $reviewer = User::where('id', $req->reviewer_id)->first();
        return response()->json([
            "msg" => "Success!",
            "data" => $verifyReviewer,
            "user"=>$reviewer
        ]);
    }

    public function checkUserifReviewer($id){
        $verifyReviewer = AssignedCapsule::where('faculty_id', auth()->user()->id)->where('capsule_id', $id)->first();
        if($verifyReviewer && $verifyReviewer->grade == null){
            return true;
        } else {
            return false;
        }
    }

    public function gradeCapsule(Request $req, $id)
    {
        $assign;
        $verifyReviewer = AssignedCapsule::where('faculty_id', auth()->user()->id)->where('capsule_id', $id)->first();
        $overall = 0;
        if($verifyReviewer){
            foreach($req->data as $rubric){
                $gradeCapsule = new GradeRubric;
                $gradeCapsule->rubrics_id = $rubric['rubricId'];
                $gradeCapsule->capsule_id = $id;
                $gradeCapsule->faculty_id = auth()->user()->id;
                $gradeCapsule->grade = $rubric['grade'];
                $gradeCapsule->save();
                $overall += $rubric['grade'];
            }
            //post comment
            $comment = new CommentCapsule;
            $comment->faculty_id = auth()->user()->id;
            $comment->capsule_id = $id;
            //can add image comment later
            $comment->comment = $req->comment;
            $comment->save();

            $verifyReviewer->grade = $overall;
            $verifyReviewer->update();
            return response()->json([
                "msg" => "Success!"
                // "assign" => $assign
            ]);
        } else {
            return response()->json([
                "data" => 'Not a reviewer in this capsule!'
            ]);
        }
    }
}
