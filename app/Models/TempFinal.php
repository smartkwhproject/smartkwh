<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempFinal extends Model
{

    protected $table = "temp_final";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hasil', 'bulan',
    ];

}
