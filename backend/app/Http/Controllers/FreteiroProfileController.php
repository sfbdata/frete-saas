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
                'whatsapp' => 'required|string|max:15',
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
            $query = FreteiroProfile::with('user');

            // Filtro por tipo de veículo (parcial)
            if ($request->filled('tipo_veiculo')) {
                $query->where('tipo_veiculo', 'like', '%' . $request->tipo_veiculo . '%');
            }

            // Filtro por cidade_base (parcial)
            if ($request->filled('cidade_base')) {
                $query->where('cidade_base', 'like', '%' . $request->cidade_base . '%');
            }

            // Ordenação opcional
            if ($request->filled('orderBy')) {
                $orderField = in_array($request->orderBy, ['avaliacao', 'quantidade_avaliacoes']) ? $request->orderBy : 'avaliacao';
                $orderDir = $request->get('orderDir', 'desc') === 'asc' ? 'asc' : 'desc';

                $query->orderBy($orderField, $orderDir);
            }

            // Paginação com per_page personalizado (padrão 10)
            $perPage = $request->get('per_page', 10);

            return response()->json($query->paginate($perPage));
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
