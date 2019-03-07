<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mcb extends Model
{

    protected $table = "mcb";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mcb_name', 'specification_mcb_id',
    ];

}
