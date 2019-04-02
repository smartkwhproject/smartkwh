<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mcb_Transaction extends Model
{

    protected $table = "mcb_transaction";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'datemcb', 'timemcb', 'current', 'voltage', 'power',
        'mcb_id', 'block_id', 'category_mcb_id',
    ];

}
