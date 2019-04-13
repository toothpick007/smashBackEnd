<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Http\Requests\UploadImageRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Facades\DB;
use Storage;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup', 'uploadUserImages']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Email or password doesnt exist'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function signup(SignUpRequest $request)
    {
        $user = User::create($request->all());
//        dd($user->id);
        File::makeDirectory(public_path() . '/images/' . $user->id, 0755, true);
        return $this->login($request);
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
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()->name
        ]);
    }

    public function currentUser()
    {
        return response()->json(request()->user());
    }

    public function uploadUserImages(UploadImageRequest $request)
    {
//        dd($request->all());
        $fileNamePreFix = 'smashndash-';
        $userId = $request->get('id');
        $file = $request->file('uploads');
        if (!empty($file)) {
//            dd($file);
            $fileName = $fileNamePreFix . $file->getClientOriginalName();
            $fileLocation = $userId . '/';
            Storage::disk('public')->putFileAs($fileLocation, $file, $fileName);

            DB::table('user_photo')->insert(
                [
                    'user_id' => $userId,
                    'link' => 'images/'. $fileLocation . $fileName,
                    'active' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );
        }
        return response()->json(['message' => 'Image Successfully Uploaded']);
    }

}