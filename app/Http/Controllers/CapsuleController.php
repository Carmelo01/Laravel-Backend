<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Capsule;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\AssignedCapsule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class CapsuleController extends Controller
{
    // to send all capsule
    public function getall()
    {
        // $capsule = Capsule::all();
        $capsule = Capsule::with(['user' => function($query) {
            $query->select('id', 'fname', 'mname', 'lname', 'email');
        }])->get();
        $capsule->makeHidden(['file_path']);
        return response()->json([
            'data'=>$capsule,
            "msg"=>"Retrieved Capsule"
        ]);

    }

    public function getOne($id)
    {
        // $capsule = Capsule::find($id);
        // $capsule = $capsule->comment()->get();
        $capsule = Capsule::with(['user'=> function($query) {
            $query->select('id', 'fname', 'mname', 'lname', 'email');
            }, 'comment.user', 'comment.admin','assigncapsule.user', 'graderubric', 'revision'])
            ->where('id', $id)->get();
        $this->setGraded($capsule, $id);
        return response()->json([
            'data'=>$capsule,
            "msg"=>"Retrieved Capsule",
        ]);
    }

    public function getOneMyCapsule($id)
    {
        // $capsule = Capsule::find($id);
        // $capsule = $capsule->comment()->get();
        $capsule = Capsule::with(['user',
            'comment.user'=> function($query) {
                $query->select('id');},
            'comment.admin','assigncapsule.user'=> function($query) {
                $query->select('id');},
            'graderubric.rubric', 'revision'])
            ->where('id', $id)->get();
        $this->setGraded($capsule, $id);
        return response()->json([
            'data'=>$capsule,
            "msg"=>"Retrieved Capsule",
        ]);
    }

    public function getOneCapsule($id)
    {
        $capsule = Capsule::with(['assigncapsule.user'=> function($query) {
            $query->select('id', 'fname', 'mname', 'lname', 'email');
        }])->where('id', $id)->get();
        $this->setGraded($capsule, $id);
        return response()->json([
            'data'=>$capsule,
            "msg"=>"Retrieved Capsule",
        ]);
    }

    public function store(Request $req) {
        // validate the incoming request data
        $validatedData = Validator::make($req->all(), [
            'title' => 'required',
            'file_path' => 'required|file|mimes:pdf|max:2048',
            //'file_path' => 'required|max:2048',
            // validate PDF file upload
            // add more validation rules as needed
        ]);

        if($validatedData->fails()){
            return response()->json([
                'msg'=>$validatedData->messages(),
            ], 403);
        }
        else {
            // create a new instance of the resource model
            $capsule  = new Capsule;
            $capsule->title = $req->input('title');
            $capsule->description = $req->input('description');
            $capsule->author_id = auth()->user()->id;
            $capsule->status = 0;
            $capsule->capsule_type = $req->input('capsule_type');
            // $capsule->date_posted = new DateTime('now');
            if($req->hasFile('file_path')) {
                {
                    $file = $req->file('file_path');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() .'capsule.'.$extension;
                    $file->move('capsule/files/',$filename);
                    $capsule->file_path = 'capsule/files/'.$filename;
                }
            }
            $capsule->save();

            // return a response indicating success
            return response()->json([
                'message' => 'Capsule created successfully.',
                'data' => $req->all()
            ], 201);
        }
    }

    public function update(Request $req, $id){
        $validatedData = Validator::make($req->all(), [
        ]);
        if($req->hasFile('file_path')){
            $validatedData = Validator::make($req->all(), [
                'file_path' => 'required|file|mimes:pdf|max:2048',
            ]);
        }
        if($validatedData->fails())
        {
            return response()->json([
                'success'=>false,
                'msg'=>$validatedData->messages(),
            ]);
        } else {
            $capsule = Capsule::find($id);
            if($capsule){
                $capsule->title = $req->input('title');
                $capsule->description = $req->input('description');
                if($req->hasFile('file_path'))
                {
                    $path = $capsule->file_path;
                    if(File::exists($path))
                    {
                        File::delete($path);
                    }
                    $file = $req->file('file_path');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() .'capsule.'.$extension;
                    $file->move('capsule/files/',$filename);
                    $capsule->file_path = 'capsule/files/'.$filename;
                }
                $capsule->update();

                return response()->json([
                    'success'=>true,
                    'msg'=>'Capsule Updated Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success'=>false,
                    'msg'=>'Post Not Found',
                ]);
            }
        }
    }

    public function softdelete(Request $req, $id){
        // use SoftDeletes;
        $capsule = Capsule::find($id);
        // $capsule->delete();
        $capsule->forceDelete();
        return response()->json([
            "msg"=>"Soft Delete Successful"
        ]);
    }

    public function restore(Request $req, $id){
        if($capsule = Capsule::withTrashed()->find($id)){
        $capsule->restore();
        return response()->json([
            "msg"=>"Restored Successfully"
        ]);
        };
            return response()->json([
                "msg"=>"Unable to Restore - File is Permanently Deleted or Missing"
            ]);

    }

    public function permadelete(Request $req, $id){
        $capsule = Capsule::withTrashed()->find($id);
        $capsule->forceDelete();
        return response()->json([
            "msg"=>"Deleted Successfully"
        ]);
    }

    // auth()->user()->fname

    public function getmycapsule(){
        $myCapsule = Capsule::with(['user' => function($query) {
            $query->select('id', 'fname', 'mname', 'lname', 'email');
        }])->where('author_id','=',auth()->user()->id)->get();
        $myCapsule->makeHidden(['file_path']);
        return response()->json([
            "data"=>$myCapsule
        ]);
    }


    // public function assignFaculty(Request $req, $id) {
    //     //$ids = json_decode($req->input('reviewer'), true);
    //     $ids = $req->all();
    //     $reviewer_array = array();
    //     $assignedReviewer = AssignedCapsule::where('capsule_id', $id)->get();

    //     foreach($assignedReviewer as $reviewer){
    //         array_push($reviewer_array, $reviewer->faculty_id);
    //     }
    //     if(count($reviewer_array) + count($ids) <= 3){
    //         foreach ($ids as $reviewer) {
    //             $user=User::where('id', $reviewer)->first();
    //             $capsule = Capsule::where('id', $id)->first();
    //             if($user){
    //                 $assignedCapsule = new AssignedCapsule;
    //                 $assignedCapsule->faculty_id = $reviewer;
    //                 $assignedCapsule->capsule_id = (int)$id;
    //                 array_push($reviewer_array, $assignedCapsule);
    //                 $assignedCapsule->save();
    //                 if(count($reviewer_array) == 3){
    //                     $capsule->status = 1;
    //                     $capsule->save();
    //                 }
    //             }
    //             // array_push($reviewer_array, $user);
    //         }
    //     } else {
    //         return response()->json([
    //             "error" => "Maximum number of reviewer is three.",
    //         ]);
    //     }
    //     return response()->json([
    //         "data" => $reviewer_array,
    //         // "capsule" => $capsule,
    //     ]);
    // }
    public function assignFaculty(Request $req, $id) {
        //$ids = json_decode($req->input('reviewer'), true);
        $ids = $req->all();
        $reviewer_array = array();
        $assignedReviewer = AssignedCapsule::where('capsule_id', $id)->get();
        foreach($assignedReviewer as $reviewer){
            array_push($reviewer_array, $reviewer->faculty_id);
        }
        foreach ($ids as $reviewer) {
            $user=User::where('id', $reviewer)->first();
            $capsule = Capsule::where('id', $id)->first();
            if($user){
                $assignedCapsule = new AssignedCapsule;
                $assignedCapsule->faculty_id = $reviewer;
                $assignedCapsule->capsule_id = (int)$id;
                array_push($reviewer_array, $assignedCapsule);
                $assignedCapsule->save();
                if(count($reviewer_array) == 3){
                    $capsule->status = 1;
                    $capsule->save();
                }
            }
        }
        return response()->json([
            "data" => $reviewer_array,
        ]);
    }

    public function dashboardData()
    {
        $unassignedCapsule = Capsule::where('status', 0)->count();
        $assignedCapsule = Capsule::where('status', 1)->count();
        $gradedCapsule = Capsule::where('status', 2)->count();
        $underRevision = Capsule::where('status', 3)->count();
        $rejectedCapsule = Capsule::where('status', 4)->count();
        $approvedCapsule = Capsule::where('status', 5)->count();

        return response()->json([
            "unassignedCapsule" => $unassignedCapsule,
            "assignedCapsule" => $assignedCapsule,
            "gradedCapsule" => $gradedCapsule,
            "underRevision" => $underRevision,
            "rejectedCapsule" => $rejectedCapsule,
            "approvedCapsule" => $approvedCapsule,
        ]);
    }

    public function lineChartData()
    {
        $janCapsule = Capsule::withTrashed()->whereMonth('created_at', 1)->count();
        $febCapsule = Capsule::withTrashed()->whereMonth('created_at', 2)->count();
        $marCapsule = Capsule::withTrashed()->whereMonth('created_at', 3)->count();
        $aprCapsule = Capsule::withTrashed()->whereMonth('created_at', 4)->count();
        $mayCapsule = Capsule::withTrashed()->whereMonth('created_at', 5)->count();
        $juneCapsule = Capsule::withTrashed()->whereMonth('created_at', 6)->count();
        $julyCapsule = Capsule::withTrashed()->whereMonth('created_at', 7)->count();
        $augCapsule = Capsule::withTrashed()->whereMonth('created_at', 8)->count();
        $septCapsule = Capsule::withTrashed()->whereMonth('created_at', 9)->count();
        $octCapsule = Capsule::withTrashed()->whereMonth('created_at', 10)->count();
        $novCapsule = Capsule::withTrashed()->whereMonth('created_at', 11)->count();
        $decCapsule = Capsule::withTrashed()->whereMonth('created_at', 12)->count();

        return response()->json([
            "janCapsule" => $janCapsule,
            "febCapsule" => $febCapsule,
            "marCapsule" => $marCapsule,
            "aprCapsule" => $aprCapsule,
            "mayCapsule" => $mayCapsule,
            "juneCapsule" => $juneCapsule,
            "julyCapsule" => $julyCapsule,
            "augCapsule" => $augCapsule,
            "septCapsule" => $septCapsule,
            "octCapsule" => $octCapsule,
            "novCapsule" => $novCapsule,
            "decCapsule" => $decCapsule,
        ]);
    }

    public function rejectCapsule(Request $req, $id){
        $capsule = Capsule::find($id);
        $capsule->status = 4;
        if($req->comment != null){
            $capsule->comment = $req->comment;
        }
        $capsule->update();

        return response()->json([
            'success'=>true,
            'msg'=>'Rejected Capsule',
        ]);
    }

    public function approveCapsule($id){
        $capsule = Capsule::find($id);
        $capsule->status = 5;
        $capsule->update();

        return response()->json([
            'success'=>true,
            'msg'=>'Approved Capsule',
        ]);
    }

    public function revisionCapsule($id){
        $capsule = Capsule::find($id);
        $capsule->status = 3;
        if($req->comment != null){
            $capsule->comment = $req->comment;
        }
        $capsule->update();

        return response()->json([
            'success'=>true,
            'msg'=>'Revise Capsule',
        ]);
    }

    public function removeReviewer($id){
        $assignReviewer = AssignedCapsule::find($id);
        $capsule = Capsule::find($assignReviewer->capsule_id);

        $assignReviewer->delete();
        $capsule->status = 0;
        $capsule->update();

        return response()->json([
            "msg"=>"Reviewer Removed Successfully"
        ]);
    }


    function setGraded($capsules, $capsuleId)
    {
        $ctr = 0;
        foreach($capsules as $capsule){
            if($capsule->status < 2){
                foreach($capsule->assigncapsule as $c){
                    if($c->grade != null){
                        $ctr++;
                    }
                }
            }
        }
        if($ctr == 3){
            $cap = Capsule::find($capsuleId);
            $cap->status = 2;
            $cap->update();
        }
    }
}
