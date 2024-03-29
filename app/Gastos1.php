<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gastos1 extends Model
{
	
	protected $table = 'expenses';
 
	protected $fillable = ['id', 'title', 'description', 'created_at', 'price'];

	public function scopeGetListaMensual($query, $year, $mes){

        $results = DB::table($this->table)
        	->selectRaw('count(id) as conteo, date(created_at) as dia, sum(price) as suma')
            ->whereRaw('YEAR(created_at) = ? ', [$year])
            ->whereRaw('month(created_at) = ?', [$mes])
            ->groupBy( DB::raw(" dia ") )
            ->orderByRaw(' created_at ASC')
            ->get();

		return $results;

	}

}
