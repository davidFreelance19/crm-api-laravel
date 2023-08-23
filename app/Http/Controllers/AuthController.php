<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\CreateUserRequest;
class AuthController extends Controller
{
    public function createUser(CreateUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'usuario creado correctamente',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
    public function loginUser(LoginRequest $request)
    {
        $isAuth =  Auth::attempt($request->only(['email', 'password'])); // attempt sirve para intentar autenticar al usuario
        if (!$isAuth) {
            return response()->json([
                'status' => 401,
                'message' => 'Hubo un error'
            ], 401);
        }
        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status' => 200,
            'message' => 'usuario autenticado correctamente',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }
}
