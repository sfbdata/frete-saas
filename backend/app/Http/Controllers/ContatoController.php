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
        $freteiro = FreteiroProfile::with(['user', 'contatosRecebidos'])->findOrFail($freteiroId);
        $userId = Auth::id();

        // ✅ Verificar se o freteiro ainda pode receber contatos (com base em contatos registrados)
        if ($freteiro->atingiuLimiteContatos()) {
            return response()->json([
                'erro' => 'Este freteiro atingiu o limite de contatos disponíveis no plano atual.'
            ], 403);
        }

        // ✅ Verificar se esse cliente já enviou esse frete para esse freteiro
        $contatoExistente = ContactSent::where('user_id', $userId)
            ->where('frete_request_id', $frete->id)
            ->where('freteiro_id', $freteiro->id)
            ->exists();

        if ($contatoExistente) {
            return response()->json([
                'erro' => 'Você já enviou este frete para este freteiro.'
            ], 409);
        }

        // ✅ Registrar contato
        ContactSent::create([
            'user_id' => $userId,
            'frete_request_id' => $frete->id,
            'freteiro_id' => $freteiro->id,
        ]);

        $freteiro->update([
            'contatos_enviados' => $freteiro->contatosRecebidos()->count(),
        ]);

        // ✅ Gerar mensagem automática
        $mensagem = "Olá, estou entrando em contato via FreteFácil!\n"
        . "*Resumo do frete:*\n"
        . "*Origem:* {$frete->origem}\n"
        . "*Destino:* {$frete->destino}\n"
        . "*Tipo de caminhão:* {$frete->tipo_caminhao}\n"
        . "*Precisa ajudante:* " . ($frete->precisa_ajudante ? 'Sim' : 'Não') . "\n"
        . "*Tem escada:* " . ($frete->tem_escada ? 'Sim' : 'Não') . "\n"
        . "*Observações:* {$frete->observacoes}";


        // ✅ Obter número real de WhatsApp
        $numeroWhatsApp = preg_replace('/[^0-9]/', '', $freteiro->whatsapp);

        if (!$numeroWhatsApp) {
            return response()->json([
                'erro' => 'Freteiro sem número de WhatsApp cadastrado.'
            ], 400);
        }

        // ✅ Criar link de redirecionamento para o WhatsApp
        $link = "https://wa.me/{$numeroWhatsApp}?text=" . urlencode($mensagem);

        return response()->json([
            'mensagem' => 'Contato registrado com sucesso.',
            'link_whatsapp' => $link,
        ]);
    }
}
