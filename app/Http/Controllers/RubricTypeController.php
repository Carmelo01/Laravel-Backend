<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Rubric;
use App\Models\RubricType;
use function App\Helpers\default_categories;
use function App\Helpers\default_rubric;

class RubricTypeController extends Controller
{
    public function store(){
        $category = Category::all()->count();
        $rubric = Rubric::all()->count();
        if($category <= 0){
            default_categories();
        }
        if($rubric <= 0){
            default_rubric();
        }
        return response()->json([
            "msg"=>"Retrieved Categories",
        ]);
    }

    public function fundedRubrics(){
        $type = Category::with('rubric')->where('type_id', 1)->get();

        return response()->json([
            "data" => $type,
        ]);
    }

    public function notFundedRubrics(){
        $type = Category::with('rubric')->where('type_id', 2)->get();

        return response()->json([
            "data" => $type,
        ]);
    }
}
