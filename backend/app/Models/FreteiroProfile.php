<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreteiroProfile extends Model
{
    use HasFactory;

        protected $fillable = [
            'user_id',              // ✅ Adicione essa linha
            'nome_completo',
            'tipo_veiculo',
            'descricao',
            'cidade_base',
            'avaliacao',
            'quantidade_avaliacoes',
            'foto_perfil',
            'foto_caminhao',
        ];

        public function user()
        {
            return $this->belongsTo(User::class);
        }
    
}
