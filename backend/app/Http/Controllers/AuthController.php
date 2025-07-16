<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Certifique-se de que esta linha está presente para usar o modelo User

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name'     => $fields['name'],
            'email'    => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas',
            ], 401);
        }

        // Gerar token de acesso
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }


    public function logout(Request $request)
    {
        // Se quiser implementar logout via tokens mais tarde
        return response()->json(['message' => 'Logout efetuado com sucesso']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}