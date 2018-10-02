<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{
    public function index(){
      $users = User::orderBy('id','DESC')->get();
      //return datatables()->collection($users)->toJson();
      return response()->json(['data' => $users], 200);
    }

    public function login(Request $request) {
      $this->validate($request, [
        'name' => 'required|string',
        'password' => 'required|string'
      ]);

      $credentials = $request->only('name', 'password');
      try {
        if (!$token = JWTAuth::attempt($credentials)) {
          return response()->json(['error' => 'El usuario y/o la contraseña son incorrectos'], 401);
        }
      } catch (JWTException $e) {
        return response()->json(['error' => 'Lo sentimos, hubo un problema. Inténtalo más tarde'], 500);
      }

      $user = JWTAuth::setToken($token)->authenticate();
      return response()->json(["token" => $token, "user" => $user], 200);
    }

    public function register(Request $request) {
      $this->validate($request, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
      ]);
  
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);

      $token = JWTAuth::fromUser($user);
      return response()->json(["token" => $token, "user" => $user], 201);
    }

    public function logout() {
      $token = JWTAuth::getToken();
      JWTAuth::invalidate($token);
    }
 
    public function refreshToken() {
      $token = JWTAuth::getToken();
      $new_token = JWTAuth::refresh($token);

      return response()->json(["token" => $new_token]);
    }
}
