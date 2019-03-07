<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{

    protected $table = "block";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_name', 'description', 'building_id',
    ];

}
