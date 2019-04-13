<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use File;
use Validator;

class UserController extends Controller
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
//        dd("WTF Happened");
        if(Auth::attempt(['name' => request('name'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        }
        else{
            dd("guess we hitting this !!!");
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        $success['name'] =  $user->name;

        //Add user into Profile Table also
        DB::table('userProfileData')->insert(
            ['user_id' => $user->id]
        );

        return response()->json(['success'=>$success], $this-> successStatus);
    }
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
    }

    /**
     * Gets the current user profile data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentUserProfileInfo()
    {
        $user = Auth::user();
        $userDetails = DB::table('userProfileData')->where('user_id', $user->id)->first();
        return response()->json(['currentUserDetails' => $userDetails], $this-> successStatus);
    }
    public function getUserProfileInfo(Request $request)
    {
//        dd($request);
        $user = Auth::user();
        if ($user) {
            $userDetails = DB::table('userProfileData')->where('user_id', $request->id)->first();
            return response()->json(['userDetails' => $userDetails], $this-> successStatus);
        } else {
            echo "You need to make an account to see profiles";
        }
    }

}