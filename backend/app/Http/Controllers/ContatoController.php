<?php

namespace App\Http\Controllers;

use App\Models\ContactSent;
use App\Models\FreteRequest;
use App\Models\FreteiroProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContatoController extends Controller
{
    public function enviarContato($freteId, $freteiroId)
    {
        $frete = FreteRequest::findOrFail($freteId);
        $freteiro = FreteiroProfile::with('user')->findOrFail($freteiroId);
        $userId = Auth::id();

        // Registrar contato
        ContactSent::create([
            'user_id' => $userId,
            'frete_request_id' => $frete->id,
            'freteiro_id' => $freteiro->id,
        ]);

        // Gerar mensagem automática
        $mensagem = "Olá, estou entrando em contato via FreteFácil!%0A"
            . "*Resumo do frete:*%0A"
            . "*Origem:* {$frete->origem}%0A"
            . "*Destino:* {$frete->destino}%0A"
            . "*Tipo de caminhão:* {$frete->tipo_caminhao}%0A"
            . "*Precisa ajudante:* " . ($frete->precisa_ajudante ? 'Sim' : 'Não') . "%0A"
            . "*Tem escada:* " . ($frete->tem_escada ? 'Sim' : 'Não') . "%0A"
            . "*Observações:* {$frete->observacoes}";

        $numero = preg_replace('/[^0-9]/', '', $freteiro->user->email); // Substitua por campo correto, ex: $freteiro->whatsapp

        // Exemplo com número fixo se não tiver whatsapp no banco
        $numeroWhatsApp = '5511999999999';

        $link = "https://wa.me/{$numeroWhatsApp}?text=" . urlencode($mensagem);

        return response()->json([
            'mensagem' => 'Contato registrado com sucesso.',
            'link_whatsapp' => $link,
        ]);
    }
}
