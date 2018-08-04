<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\User;
use JWTAuth;

class AuthController extends Controller
{
    private $rules = [
        'name' => 'required',
        'email' => 'required|unique:users',
        'password' => 'required',
    ];

    public function register(Request $request)
    {

        $this->validate($request, $this->rules);

        $user = User::create([
            'name' => $request->json('name'),
            'email' => $request->json('email'),
            'password' => bcrypt($request->json('password'))
        ]);

        return response()->json(['success' => 'Success to create User.'], 200);

    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required', 'password' => 'required',
        ]);

        $getUser = User::select('*')
            ->where('email', $request->json('email'))
            ->first();

        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'invalid_credentials',
                    'status_error' => 3
                ], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // update token to db
        $getUser->remember_token = $token;
        $getUser->save();

        // all good so return the token
        return response()->json([
            'token' => $token,
            'username' => $getUser->name
        ]);

    }

    public function logout()
    {

        $token = JWTAuth::getToken();
        $destroy = JWTAuth::invalidate($token);

        return response()->json(['success' => 'Berhasil Logout'], 200);

    }

    public function check()
    {
        return response()->json(['success' => 'Valid'], 200);
    }

}
