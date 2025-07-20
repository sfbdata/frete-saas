<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContactSent;


class FreteiroProfile extends Model
{
    use HasFactory;

        protected $fillable = [
            'user_id',              // ✅ Adicione essa linha
            'nome_completo',
            'whatsapp',
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

        public function contatosRecebidos()
        {
            return $this->hasMany(ContactSent::class, 'freteiro_id');
        }

        public function atingiuLimiteContatos(): bool
        {
            return $this->contatosRecebidos()->count() >= $this->limite_contatos;
        }
    
}
