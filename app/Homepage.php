<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homepage extends Model
{

    protected $table = 'homepage';

    protected $primaryKey = 'id';

    protected $fillable = [
        'key',
        'label',
        'value',
    ];
}
