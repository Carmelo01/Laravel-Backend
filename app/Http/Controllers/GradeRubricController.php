<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GradeRubric;
use App\Models\AssignedCapsule;
use App\Models\CommentCapsule;
use App\Models\Rubric;
use App\Models\User;

class GradeRubricController extends Controller
{
    public function getGradedRubrics(Request $req){
        $verifyReviewer = GradeRubric::where('faculty_id', $req->reviewer_id)->where('capsule_id', $req->capsule_id)->get();
        $reviewer = User::where('id', $req->reviewer_id)->first();
        $grade = AssignedCapsule::where('faculty_id', $req->reviewer_id)->where('capsule_id', $req->capsule_id)->first();
        return response()->json([
            "msg" => "Success!",
            "data" => $verifyReviewer,
            "user"=>$reviewer,
            "grade"=>$grade
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

    // public function gradeCapsule(Request $req, $id)
    // {
    //     $assign;
    //     $verifyReviewer = AssignedCapsule::where('faculty_id', auth()->user()->id)->where('capsule_id', $id)->first();
    //     $overall = 0;
    //     if($verifyReviewer){
    //         foreach($req->data as $rubric){
    //             $rubricData = Rubric::with('category')->find($rubric['rubricId']);
    //             $gradeCapsule = new GradeRubric;
    //             $gradeCapsule->rubric = $rubricData->rubric;
    //             $gradeCapsule->category = $rubricData->category->title;
    //             $gradeCapsule->capsule_id = $id;
    //             $gradeCapsule->faculty_id = auth()->user()->id;
    //             $gradeCapsule->grade = $rubric['grade'];
    //             $gradeCapsule->save();
    //             $overall += $rubric['grade'];
    //         }
    //         //post comment
    //         $comment = new CommentCapsule;
    //         $comment->faculty_id = auth()->user()->id;
    //         $comment->capsule_id = $id;
    //         //can add image comment later
    //         $comment->comment = $req->comment;
    //         $comment->save();

    //         $verifyReviewer->grade = $overall;
    //         $verifyReviewer->update();
    //         return response()->json([
    //             "msg" => "Success!"
    //             // "assign" => $assign
    //         ]);
    //     } else {
    //         return response()->json([
    //             "data" => 'Not a reviewer in this capsule!'
    //         ]);
    //     }
    // }
    public function gradeCapsule(Request $req, $id)
    {
        $sad;
        $assign;
        $verifyReviewer = AssignedCapsule::where('faculty_id', auth()->user()->id)->where('capsule_id', $id)->first();
        $overall = 0;
        $cnt = 0;
        if($verifyReviewer){
            $dataRubrics = $req->data;
            for($i = 0; $i < count($dataRubrics); $i++){
                $scorePerCategory = 0;
                $ctr = 0;
                for($j = 0; $j < count($dataRubrics[$i]['questions']); $j++){
                    $questionsObj = $dataRubrics[$i]['questions'][$j];
                    $rubricData = Rubric::with('category')->find($questionsObj['questionId']);
                    $gradeCapsule = new GradeRubric;
                    $gradeCapsule->rubric = $rubricData->rubric;
                    $gradeCapsule->category = $rubricData->category->title;
                    $gradeCapsule->capsule_id = $id;
                    $gradeCapsule->faculty_id = auth()->user()->id;
                    $gradeCapsule->grade = $questionsObj['grade'];
                    $gradeCapsule->save();
                    $scorePerCategory += $questionsObj['grade'];
                    $ctr++;
                }
                if($req->type == 0){
                    if($cnt == 2){
                        $overall += ($scorePerCategory/($ctr*5)) * 20;
                    }else{
                        $overall += ($scorePerCategory/($ctr*5)) * 40;
                    }
                } else if($req->type == 1){
                    $overall += ($scorePerCategory/($ctr*5)) * 50;
                } else {
                    $overall += $scorePerCategory;
                }
                $cnt+=1;
            }
            $comment = new CommentCapsule;
            $comment->faculty_id = auth()->user()->id;
            $comment->capsule_id = $id;
            //can add image comment later
            $comment->comment = $req->comment;
            $comment->save();

            $verifyReviewer->grade = $overall;
            $verifyReviewer->update();
            return response()->json([
                "msg" => "Success!",
                "overall" => $overall
                // "assign" => $assign
            ]);
        } else {
            return response()->json([
                "data" => 'Not a reviewer in this capsule!'
            ]);
        }
    }
}
