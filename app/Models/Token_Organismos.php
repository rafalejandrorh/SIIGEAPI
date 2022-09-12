<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token_Organismos extends Model
{
    use HasFactory;

    protected $table = 'token_organismos';

    protected $fillable = ['id','id_dependencias', 'token', 'last_used_at', 'created_at', 'expires_at', 'duracion_token'];

    public function Dependencias()
    {
        return $this->belongsto(Dependencias::class, 'id_dependencias');
    }
}
