<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSent extends Model
{
    protected $fillable = [
        'user_id',
        'frete_request_id',
        'freteiro_id',
    ];
}
