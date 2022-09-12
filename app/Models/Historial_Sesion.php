<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial_Sesion extends Model
{
    use HasFactory;

    protected $table = 'historial_sesion';

    public function user()
    {
        return $this->belongsto(User::class, 'id_user');
    }
}
