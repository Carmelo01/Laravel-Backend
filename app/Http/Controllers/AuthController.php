<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Admin;

class AuthController extends Controller
{
    //Faculty/User
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    //login function
    public function login(Request $req)
    {
        $user = User::where('email', '=', $req->email)->first();
        if(empty($user)){

            return response()->json([
                'success'=>false,
                'error'=>'Email not registered',
            ], 401);
        }
        elseif ($user->status == 0){
            return response()->json([
                'success'=>false,
                'error'=>'Email not verified',
            ], 401);
        }
        else {
            $credentials = request(['email', 'password']);
            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Email or password doesn\'t exist'], 401);
            }
            return $this->respondWithToken($token);
        }
    }

    //sign up function
    public function signup(SignUpRequest $request){
        //add validation to user
        $validatedData = Validator::make($request->all(), [
            'email' => 'unique:users,email',
        ]);
        if($validatedData->fails()){
            return response()->json([
                'error'=>$validatedData->messages(),
            ]);
        }else{
            $user = User::create($request->all());
            return response()->json([
                'user' => $user,
                'msg' => 'Register successful '
            ]);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()->fname.' '.auth()->user()->mname.' '.auth()->user()->lname
        ]);
    }

}
