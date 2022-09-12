<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencias extends Model
{
    use HasFactory;

    protected $table = 'dependencias';

    protected $fillable = ['id','Nombre', 'Ministerio', 'Organismo', 'id_person'];

    public function person()
    {
        return $this->belongsto(Person::class, 'id_person');
    }
}
