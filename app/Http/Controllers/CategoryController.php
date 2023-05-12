<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\RubricType;
use function App\Helpers\default_rubric_types;


class CategoryController extends Controller
{
    public function index(){
        $categories = Category::with(['rubric'])
            ->where('type_id', '3')
            ->get();
        $type = RubricType::all()->count();
        if($type <= 0){
            default_rubric_types();
        }
        return response()->json([
            'data'=>$categories,
            "msg"=>"Retrieved Categories",
        ]);
    }

    public function getOne($id){
        $category = Category::with(['rubric'])->where('id', $id)->get();

        return response()->json([
            'data'=>$category,
            "msg"=>"Retrieved Categories"
        ]);
    }

    public function store(Request $req) {
        // create a new instance of the resource model
        $category  = new Category;
        $category->title = $req->input('title');
        $category->type_id = '3';
        // set other fields as needed
        $category->save();

        // return a response indicating success
        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category
        ], 201);
    }

    public function edit(Request $req, $id) {
        // create a new instance of the resource model
        $category  = Category::find($id);
        if($category){
            $category->title = $req->input('title');
            $category->update();

            // return a response indicating success
            return response()->json([
                'message' => 'Category updated successfully.',
                'data' => $category
            ], 201);
        } else {
            return response()->json([
                'success'=>false,
                'msg'=>'Category Not Found',
            ]);
        }
    }

    public function softdelete(Request $req, $id){
        // use SoftDeletes;
        $category = Category::find($id);
        $category->delete();
        return response()->json([
            "msg"=>"Soft Delete Successful"
        ]);
    }

    public function restore(Request $req, $id){
        if($category = Category::withTrashed()->find($id)){
            $category->restore();
            return response()->json([
                "msg"=>"Restored Successfully"
            ]);
        };
        return response()->json([
            "msg"=>"Unable to Restore - File is Permanently Deleted or Missing"
        ]);

    }

    public function permadelete(Request $req, $id){
        $category = Category::withTrashed()->find($id);
        $category->forceDelete();
        return response()->json([
            "msg"=>"Deleted Successfully"
        ]);
    }

}
