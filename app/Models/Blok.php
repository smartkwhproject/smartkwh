<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blok extends Model
{

    protected $table = "blok";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_blok', 'deskripsi', 'gedung_id',
    ];

}
