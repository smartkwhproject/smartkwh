<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification_mcb extends Model
{

    protected $table = "specification_mcb";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'colour', 'weight', 'healty_status', 'max_voltage', 'power_factor',
    ];

}
