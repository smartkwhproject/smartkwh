<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryMcb extends Model
{

    protected $table = "category_mcb";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name', 'min', 'max',
    ];

}
