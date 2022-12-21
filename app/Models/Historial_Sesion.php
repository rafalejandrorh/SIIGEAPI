<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial_Sesion extends Model
{
    use HasFactory;

    protected $table = 'historial_sesion';

    protected $fillable = ['logout', 'tipo_logout', 'id_user', 'id', 'MAC'];

    public function user()
    {
        return $this->belongsto(User::class, 'id_user');
    }
}
