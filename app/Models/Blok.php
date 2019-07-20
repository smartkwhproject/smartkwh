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

    public function gedung()
    {
        return $this->hasOne(Gedung::class, 'id', 'gedung_id');
    }

}
