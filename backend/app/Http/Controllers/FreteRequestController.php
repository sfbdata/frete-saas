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
        $data = $request->validate([
            'nome_cliente' => 'required|string|max:255',
            'whatsapp_cliente' => 'required|string|max:15',
            'origem' => 'required|string',
            'destino' => 'required|string',
            'tipo_caminhao' => 'required|string',
            'precisa_ajudante' => 'nullable|boolean',
            'tem_escada' => 'nullable|boolean',
            'observacoes' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        $frete = FreteRequest::create($data);

        // (Por enquanto) retornar todos os freteiros cadastrados
        $freteiros = FreteiroProfile::with('user')->take(10)->get();

        return response()->json([
            'mensagem' => 'Solicitação registrada com sucesso.',
            'frete' => $frete,
            'freteiros_disponiveis' => $freteiros
        ]);
    }
}
