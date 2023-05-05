<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rubric;

class RubricController extends Controller
{
    public function index(){
        $rubrics = Rubric::with(['graderubric'])->get();

        return response()->json([
            'data'=>$rubrics,
            "msg"=>"Retrieved Rubrics"
        ]);
    }

    public function store(Request $req, $id) {
        $rubricsReq = $req->all();
        // for ($i = 0; $i < count($rubricsReq); $i++) {
        //     $rubric  = new Rubric;
        //     $rubric->rubric = $rubricsReq[$i]->value;
        //     $rubric->category_id = $id;
        //     array_push($rubric_array, $rubric);
        //     $rubric->save();
        // }
        foreach ($rubricsReq as $rubricReq) {
            foreach($rubricReq as $r){
                $rubric  = new Rubric;
                $rubric->rubric = $r;
                $rubric->category_id = $id;
                $rubric->save();
            }
        }
        return response()->json([
            'message' => 'Rubrics created successfully.',
        ], 201);
    }

    public function edit(Request $req, $id) {
        $rubric  = Rubric::find($id);
        if($rubric){
            $rubric->rubric = $req->input('rubric');
            $rubric->update();
            return response()->json([
                'message' => 'Rubric updated successfully.',
                'data' => $rubric
            ], 201);
        } else {
            return response()->json([
                'success'=>false,
                'msg'=>'Rubric Not Found',
            ]);
        }
    }

    public function softdelete(Request $req, $id){
        // use SoftDeletes;
        $rubric = Rubric::find($id);
        $rubric->delete();
        return response()->json([
            "msg"=>"Soft Delete Successful"
        ]);
    }

    public function restore(Request $req, $id){
        if($rubric = Rubric::withTrashed()->find($id)){
            $rubric->restore();
            return response()->json([
                "msg"=>"Restored Successfully"
            ]);
        };
        return response()->json([
            "msg"=>"Unable to Restore - File is Permanently Deleted or Missing"
        ]);

    }

    public function permadelete(Request $req, $id){
        $rubric = Rubric::withTrashed()->find($id);
        $rubric->forceDelete();
        return response()->json([
            "msg"=>"Deleted Successfully"
        ]);
    }
}
