<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiMcb extends Model
{

    protected $table = "transaksi_mcb";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'blok_id', 'tanggal', 'waktu',
        'va', 'vb', 'vc',
        'vab', 'vbc', 'vca',
        'ia', 'ib', 'ic',
        'pa', 'pb', 'pc', 'pt',
        'pfa', 'pfb', 'pfc',
        'ep', 'eq', 'kwh',
    ];

    public function createData($payload)
    {
        return TransaksiMcb::create($payload);
    }

}
