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

    public function index(Request $request)
        {
            $query = \App\Models\FreteiroProfile::with('user');

            // 🔍 Filtros opcionais
            if ($request->filled('cidade')) {
                $query->where('cidade_base', 'like', '%' . $request->cidade . '%');
            }

            if ($request->has('tipo_veiculo')) {
                $query->where('tipo_veiculo', 'like', '%' . $request->tipo_veiculo . '%');
            }


            if ($request->filled('avaliacao_min')) {
                $query->where('avaliacao', '>=', floatval($request->avaliacao_min));
            }

            // 🔃 Ordenação
            if ($request->filled('ordenar_por') && $request->ordenar_por === 'avaliacao') {
                $query->orderByDesc('avaliacao');
            } else {
                $query->orderByDesc('id'); // mais recentes primeiro
            }

            // 📦 Paginação
            $freteiros = $query->select(
                'id', 'user_id', 'nome_fantasia', 'nome_completo',
                'tipo_veiculo', 'cidade_base', 'avaliacao',
                'quantidade_avaliacoes', 'foto_perfil'
            )->paginate(10);

            return response()->json($freteiros);
        }



    public function show($id)
        {
            $freteiro = \App\Models\FreteiroProfile::with('user')->find($id);

            if (!$freteiro) {
                return response()->json(['erro' => 'Freteiro não encontrado'], 404);
            }

            return response()->json($freteiro);
        }

        

}
