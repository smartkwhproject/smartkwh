<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecificationMcb extends Model
{

    protected $table = "specification_mcb";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'colour', 'max_stream', 'max_voltage',
    ];

}
