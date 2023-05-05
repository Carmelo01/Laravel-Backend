<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CapsuleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentCapsuleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RubricController;
use App\Http\Controllers\GradeRubricController;
use App\Http\Controllers\ContentManagementController;
use App\Http\Controllers\CapsuleRevisionController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($facultyRouter) {

    Route::post('faculty/Login', [AuthController::class, 'login']);
    Route::post('faculty/Signup', [AuthController::class, 'signup']);
    // Route::post('faculty/Refresh', [AuthController::class, 'refresh']);
    // Route::view('faculty/Log', [AdminController::class, 'facultylog']); Wala pa nito
    Route::post('faculty/SendPasswordResetLink', [ResetPasswordController::class, 'sendEmail']); //@sendEmail daw na ipapasok
    Route::post('faculty/ResetPassword', [ChangePasswordController::class, 'process']); //@process daw na ipapasok
    Route::group(['middleware' => 'auth:user'], function(){
        Route::get('faculty/Me', [AuthController::class, 'me']);
        Route::post('faculty/Logout', [AuthController::class, 'logout']);
    });
});

    Route::post('admin/Register', [AdminController::class, 'adminregister']);
    Route::post('admin/Login', [AdminController::class, 'adminlogin']);
    // Route::view('admin/Log', [AdminController::class, 'adminlog']);
    // Route::post('adminRefresh', [AdminControllerr::class, 'refresh']);
    Route::group(['middleware' => 'admin:admin'], function(){
        Route::get('admin/Me', [AdminController::class, 'adminme']);
        Route::post('admin/Logout', [AdminController::class, 'adminlogout']);
});


Route::post('assignFaculty/{id}', [CapsuleController::class, 'assignFaculty']);

Route::get('faculty', [FacultyController::class, 'index']);

//faculty need login user
Route::group(['middleware' => 'auth:user'], function(){
    //capsule
    Route::post('capsule/create', [CapsuleController::class, 'store']);
    Route::get('myCapsule', [CapsuleController::class, 'getmycapsule']);

    //get assigned capsule to the reviewer
    Route::get('faculty/capsule/getAssigned' , [FacultyController::class, 'getAssignedCapsule']);

    //edit faculty profile
    Route::post('faculty/edit/profile', [FacultyController::class, 'editProfile']);

    //grade capsule
    Route::post('capsule/review/grade/{id}', [GradeRubricController::class, 'gradeCapsule']);
    Route::get('check/currentUser/is_valid/{id}', [GradeRubricController::class, 'checkUserifReviewer']);
});
   //grade capsule
Route::post('graded/rubric/get', [GradeRubricController::class, 'getGradedRubrics']);

//admin need login admin
Route::group(['middleware' => 'auth:admin'], function(){
    //comment
    Route::post('admin/postComment/{id}' , [CommentCapsuleController::class, 'store']);
});

 //comment
Route::get('faculty/getComments/{id}' , [CommentCapsuleController::class, 'getAll']);
Route::post('comment/post/{id}' , [CommentCapsuleController::class, 'store']);

//capsule
Route::get('capsule/index', [CapsuleController::class, 'getall']);
Route::get('capsule/{id}/get', [CapsuleController::class, 'getOne']);
Route::get('capsule/click/{id}', [CapsuleController::class, 'getOneCapsule']);
Route::get('capsule/currentUser/getOne/{id}', [CapsuleController::class, 'getOneMyCapsule']);
Route::delete('capsule/softdelete/{id}', [CapsuleController::class, 'softdelete']);
Route::get('capsule/restore-data/{id}', [CapsuleController::class, 'restore']);
Route::delete('capsule/delete/{id}', [CapsuleController::class, 'permadelete']);


// Route::get('user', [AuthController::class, 'user']);
Route::get('faculty/index' , [FacultyController::class, 'index']);
Route::get('faculty/unverified' , [FacultyController::class, 'getUnverifiedFaculty']);
Route::get('faculty/get/both' , [FacultyController::class, 'getVerifiedandUnverified']);
// Route::post('faculty/create', [FacultyController::class, 'store']);
Route::delete('faculty/softdelete/{id}', [FacultyController::class, 'softdelete']);
Route::get('faculty/restore-data/{id}', [FacultyController::class, 'restore']);
Route::delete('faculty/delete/{id}', [FacultyController::class, 'permadelete']);


//rubrics category
Route::get('category/index', [CategoryController::class, 'index']);
Route::get('category/getOne/{id}', [CategoryController::class, 'getOne']);
Route::post('category/add', [CategoryController::class, 'store']);
Route::post('category/edit/{id}', [CategoryController::class, 'edit']);
Route::delete('category/soft_delete/{id}', [CategoryController::class, 'softdelete']);
Route::get('category/restore/{id}', [CategoryController::class, 'restore']);
Route::delete('category/delete/{id}', [CategoryController::class, 'permadelete']);

//rubrics
Route::get('rubrics/index', [RubricController::class, 'index']);
Route::post('rubrics/add/{id}', [RubricController::class, 'store']);
Route::post('rubrics/edit/{id}', [RubricController::class, 'edit']);
Route::delete('rubrics/soft_delete/{id}', [RubricController::class, 'softdelete']);
Route::get('rubrics/restore/{id}', [RubricController::class, 'restore']);
Route::delete('rubrics/delete/{id}', [RubricController::class, 'permadelete']);


//admin verify user
Route::post('admin/verify/faculty/{id}', [AdminController::class, 'verifyFaculty']);

//content management
Route::get('admin/content/index', [ContentManagementController::class, 'index']);
Route::post('admin/content/edit/logo/{id}', [ContentManagementController::class, 'updateCict']);
Route::post('admin/content/edit/sideNav/{id}', [ContentManagementController::class, 'updateSideNav']);

//Edit admin profile
Route::post('admin/edit/profile/{id}', [AdminController::class, 'editProfile']);

//Edit capsule status
Route::post('capsule/reject/{id}', [CapsuleController::class, 'rejectCapsule']);
Route::post('capsule/approve/{id}', [CapsuleController::class, 'approveCapsule']);
Route::post('capsule/revise/{id}', [CapsuleController::class, 'revisionCapsule']);
Route::post('capsule/remove/reviewer/{id}', [CapsuleController::class, 'removeReviewer']);


//Revision Capsule
Route::post('revise/capsule/upload/{id}', [CapsuleRevisionController::class, 'store']);
Route::get('revise/capsule/list', [CapsuleRevisionController::class, 'getall']);
Route::get('revise/capsule/{id}/view-revision', [CapsuleRevisionController::class, 'getAllofOne']);

//Edit capsule
Route::post('capsule/edit/{id}', [CapsuleController::class, 'update']);

//dashboard
Route::get('dashboard/data/get', [CapsuleController::class, 'dashboardData']);
Route::get('dashboard/chartdata/get', [CapsuleController::class, 'lineChartData']);


// pdf get
// Route::get('/file/{filename}', function ($filename) {
//     $newString = str_replace('-', '/', $filename);
//     $path = storage_path($newString);
//     if (!File::exists($path)) {
//         abort(404);
//     }
//     $file = File::get($path);
//     $type = File::mimeType($path);
//     $response = Response::make($file, 200);
//     $response->header("Content-Type", $type);
//     return $response;
// });

//
// Route::get('/file/{filename}', function ($filename) {
// $path = 'app/' . $filename;
//   $headers = [
//     'Content-Type' => 'application/pdf',
//     'Content-Disposition' => 'inline; filename="' . $filename . '"'
//   ];
//   return new StreamedResponse(function () use ($path) {
//     echo Storage::get($path);
//   }, 200, $headers);
// });


Route::get('/file/{filename}', function ($filename) {
    $path = str_replace('-', '/', $filename);
    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, X-Auth-Token, Origin, Authorization',
    ]);
});
