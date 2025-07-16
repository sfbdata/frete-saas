<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreteRequest extends Model
{
    protected $fillable = [
        'nome_cliente',
        'whatsapp_cliente',
        'origem',
        'destino',
        'tipo_caminhao',
        'precisa_ajudante',
        'tem_escada',
        'observacoes',
    ];
}
