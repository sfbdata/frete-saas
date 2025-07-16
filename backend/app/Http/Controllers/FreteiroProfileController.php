<?php

namespace App\Http\Controllers;

use App\Models\FreteiroProfile;
use Illuminate\Http\Request;
use Exception;

class FreteiroProfileController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'error' => 'Usuário não autenticado.',
                ], 401);
            }

            $data = $request->validate([
                'nome_completo' => 'required|string',
                'tipo_veiculo' => 'required|string',
                'descricao' => 'nullable|string',
                'cidade_base' => 'required|string',
                // fotos e avaliações podem ser nulas por enquanto
            ]);

            $profile = FreteiroProfile::updateOrCreate(
                ['user_id' => $user->id],
                array_merge($data, [
                    'avaliacao' => 0,
                    'quantidade_avaliacoes' => 0,
                ])
            );

            return response()->json($profile, 201);

        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
