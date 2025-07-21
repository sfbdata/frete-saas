<?php

namespace App\Http\Controllers;

use App\Models\FreteiroProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'nome_completo'   => 'required|string|max:255',
                'whatsapp'        => 'required|string|regex:/^\+?[0-9\s\-\(\)]{10,20}$/',
                'tipo_veiculo'    => 'required|string|max:255',
                'descricao'       => 'nullable|string',
                'cidade_base'     => 'required|string|max:255',
            ]);

            // Limpa o número de WhatsApp para conter apenas dígitos
            $data['whatsapp'] = preg_replace('/\D+/', '', $data['whatsapp']);

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

    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();
            $profile = FreteiroProfile::where('id', $id)->where('user_id', $user->id)->first();

            if (!$profile) {
                return response()->json(['error' => 'Perfil não encontrado ou não pertence ao usuário.'], 403);
            }

            $data = $request->validate([
                'nome_completo'   => 'sometimes|required|string|max:255',
                'whatsapp'        => 'sometimes|required|string|regex:/^\+?[0-9\s\-\(\)]{10,20}$/',
                'tipo_veiculo'    => 'sometimes|required|string|max:255',
                'descricao'       => 'nullable|string',
                'cidade_base'     => 'sometimes|required|string|max:255',
            ]);

            if (isset($data['whatsapp'])) {
                $data['whatsapp'] = preg_replace('/\D+/', '', $data['whatsapp']);
            }

            $profile->update($data);

            return response()->json($profile);

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
        try {
            $query = FreteiroProfile::with(['user', 'contatosRecebidos'])
                ->withCount('contatosRecebidos')
                ->having('contatos_recebidos_count', '<', \DB::raw('limite_contatos'));

            if ($request->filled('tipo_veiculo')) {
                $query->where('tipo_veiculo', 'like', '%' . $request->tipo_veiculo . '%');
            }

            if ($request->filled('cidade_base')) {
                $query->where('cidade_base', 'like', '%' . $request->cidade_base . '%');
            }

            if ($request->filled('orderBy')) {
                $orderField = in_array($request->orderBy, ['avaliacao', 'quantidade_avaliacoes']) ? $request->orderBy : 'avaliacao';
                $orderDir = $request->get('orderDir', 'desc') === 'asc' ? 'asc' : 'desc';
                $query->orderBy($orderField, $orderDir);
            }

            $perPage = $request->get('per_page', 10);

            return response()->json($query->paginate($perPage));

        } catch (\Throwable $e) {
            return response()->json([
                'erro' => 'Erro ao carregar os freteiros: ' . $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine()
            ], 500);
        }
    }

    public function show($id)
    {
        $freteiro = FreteiroProfile::with('user')->find($id);

        if (!$freteiro) {
            return response()->json(['erro' => 'Freteiro não encontrado'], 404);
        }

        return response()->json($freteiro);
    }

    public function dashboard(Request $request)
    {
        try {
            $user = $request->user();

            $profile = FreteiroProfile::withCount('contatosRecebidos')->where('user_id', $user->id)->first();

            if (!$profile) {
                return response()->json([
                    'erro' => 'Perfil do freteiro não encontrado.'
                ], 404);
            }

            // Cálculo dos contatos restantes com base no limite
            $contatosRecebidos = $profile->contatos_recebidos_count;
            $limiteContatos = $profile->limite_contatos;
            $contatosRestantes = max($limiteContatos - $contatosRecebidos, 0);

            return response()->json([
                'nome_completo' => $profile->nome_completo,
                'tipo_veiculo' => $profile->tipo_veiculo,
                'cidade_base' => $profile->cidade_base,
                'descricao' => $profile->descricao,
                'contatos_recebidos' => $contatosRecebidos,
                'contatos_restantes' => $contatosRestantes,
                'limite_contatos' => $limiteContatos,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'erro' => 'Erro ao carregar o painel do freteiro.',
                'mensagem' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine()
            ], 500);
        }
    }

}
