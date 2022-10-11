<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencias_Servicios extends Model
{
    use HasFactory;

    protected $table = 'dependencias_servicios';

    public function dependencias()
    {
        return $this->belongsto(Dependencias::class, 'id_dependencias');
    }

    public function servicios()
    {
        return $this->belongsto(Servicios::class, 'id_servicios');
    }
}
