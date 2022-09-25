<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;

    protected $table = 'questions';

    public function questions()
    {
        return $this->belongsto(Documentacion::class,'id');
    }

}
