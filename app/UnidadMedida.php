<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{

	protected $table = 'unidadmedidavarios';

	protected $fillable = ['clave', 'nombre', 'tipo', 'origen', ];
	
    
}
