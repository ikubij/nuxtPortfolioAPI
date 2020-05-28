<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','me']]);
    }

        /**
     * Register a new user
     */
    public function register(Request $request)
    { 
        $exist=User::where('email','=',$request->email)->first();
        if($exist){
            return response()->json(['error' => 'Email Exists']);
        }else{  
            $user = new User();
            $user->name = $request->name;   
            $user->email = $request->email;
            $user->password = bcrypt($request->password);           
            $user->save();


            // $credentials = $request->only(['email', 'password']);

            // if (! $token = auth('api')->attempt($credentials)) {
            //     return response()->json(['error' => 'Unauthorized'], 401);
            // }
            
            // return $this->respondWithToken($token);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {   
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $this->respondWithToken($token);
    }

    //checks if email already in DB
    public function uniqueEmail(Request $request) {
        $unique = !User::where('email', $request->email)->exists();
        return response()->json(['data' => $unique], 200);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(['user'=>auth('api')->user()]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        // \JWTAuth::invalidate(\JWTAuth::getToken());

        return response()->json(['success' => true]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
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
            'success'=>true,
            'token' => $token,
            'user' => $this->guard()->user(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function guard() {
        return \Auth::guard('api');
    }

}
