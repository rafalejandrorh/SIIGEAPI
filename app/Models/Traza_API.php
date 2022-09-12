<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traza_API extends Model
{
    use HasFactory;

    protected $table = 'trazas_api';

    protected $fillable = ['ip','mac', 'usuario', 'fecha_request', 'action', 'response', 'request', 'token', 'ente'];
}
