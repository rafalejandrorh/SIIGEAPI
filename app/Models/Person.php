<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    protected $dates = ['fecha_nacimiento'];
    protected $fillable = ['id_tipo_documentacion', 'letra_cedula', 'cedula', 
    'primer_nombre', 'segundo_nombre', 'primer_apellido' , 'segundo_apellido', 'id_genero', 'fecha_nacimiento', 
    'telefono', 'correo_electronico'];

    public function person()
    {
        return $this->hasOne(Funcionario::class);
    }

    // relacion de uno a uno con la tabla funcionario
    public function resennado()
    {
        return $this->hasOne(Resenna::class, 'id_person');
    }

    public function documentacion()
    {
        return $this->belongsto(Documentacion::class,'id_tipo_documentacion');
    }

    public function genero()
    {
        return $this->belongsto(Genero::class,'id_genero');
    }

}
