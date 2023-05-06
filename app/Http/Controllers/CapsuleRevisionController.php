<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Revision;
use App\Models\Capsule;

class CapsuleRevisionController extends Controller
{
    public function getall()
    {
        // $capsule = Capsule::all();
        $revisedCapsule = Capsule::with(['user' => function($query) {
            $query->select('id', 'fname', 'mname', 'lname', 'email');
        }, 'revision'])->get();

        return response()->json([
            'data'=>$revisedCapsule,
            "msg"=>"Retrieved Capsule Revision"
        ]);

    }

    public function store(Request $req, $id) {
        $validatedData = Validator::make($req->all(), [
            'title' => 'required',
            'file_location' => 'required|file|mimes:pdf|max:2048',
        ]);

        if($validatedData->fails()){
            return response()->json([
                'msg'=>$validatedData->messages(),
            ], 401);
        }
        else {
            $revisedCapsule = new Revision;
            $revisedCapsule->title = $req->input('title');
            $revisedCapsule->comment = $req->input('description');
            $revisedCapsule->capsule_id = $id;

            if($req->hasFile('file_location')){
                {
                    $file = $req->file('file_location');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() .'revisedCapsule.' .$extension;
                    $file->move('revisedCapsule/', $filename);
                    $revisedCapsule->file_location = 'revisedCapsule/'.$filename;

                }
            }
            $revisedCapsule->save();
            return response()->json([
                'message' => 'Revision has been uploaded.',
                'data' => $revisedCapsule
            ], 201);
        }

    }

    // public function getAllofOne($id)
    // {
    //     $revisedCapsule = Capsule::with('revision' )->where('id', $id)->get();

    //     return response()->json([
    //         'data'=>$revisedCapsule,
    //         "msg"=>"Retrieved Revised Capsule/s"
    //     ]);
    // }




}
