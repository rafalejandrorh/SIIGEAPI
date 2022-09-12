<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token_Historial extends Model
{
    use HasFactory;

    protected $table = 'token_historial';

    protected $fillable = ['id','id_dependencias', 'token', 'created_at', 'expires_at', 'last_used_at', 'duracion_token'];

    public function Dependencias()
    {
        return $this->belongsto(Dependencias::class, 'id_dependencias');
    }
}
