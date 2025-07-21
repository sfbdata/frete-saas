<?php

namespace App\Http\Controllers;

use App\Models\FreteRequest;
use App\Models\FreteiroProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FreteRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            // ✅ Validação dos dados do frete
            $data = $request->validate([
                'nome_cliente'      => 'required|string|max:255',
                'whatsapp_cliente'  => 'required|string|max:15',
                'origem'            => 'required|string',
                'destino'           => 'required|string',
                'tipo_caminhao'     => 'required|string',
                'precisa_ajudante'  => 'nullable|boolean',
                'tem_escada'        => 'nullable|boolean',
                'observacoes'       => 'nullable|string',
            ]);

            // ✅ Associar o usuário logado, se houver (útil para rastreamento do frete)
            $data['user_id'] = Auth::id();

            // ✅ Criar a solicitação de frete
            $frete = FreteRequest::create($data);

            // ✅ Buscar freteiros com limite de contatos não atingido
            $freteiros = FreteiroProfile::with('user')
                ->withCount('contatosRecebidos') // Adiciona contatos_recebidos_count
                ->having('contatos_recebidos_count', '<', \DB::raw('limite_contatos'))
                ->take(10)
                ->get();

            // ✅ Resposta de sucesso com os freteiros disponíveis
            return response()->json([
                'mensagem' => 'Solicitação registrada com sucesso.',
                'frete' => $frete,
                'freteiros_disponiveis' => $freteiros
            ]);

        } catch (\Throwable $th) {
            // 🛡️ Captura de qualquer erro com detalhes úteis para debug
            return response()->json([
                'erro'     => 'Erro ao registrar solicitação: ' . $th->getMessage(),
                'mensagem' => $th->getMessage(),
                'arquivo'  => $th->getFile(),
                'linha'    => $th->getLine()
            ], 500);
        }
    }
}
        