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
        'tglmcb', 'jammcb',
        'I1', 'I2', 'I3',
        'V1', 'V2', 'V3',
        'VAB', 'VAC', 'VBC',
        'PF', 'wh', 'kwh',
        'blok_id',
    ];

    public function createData($payload)
    {
        return TransaksiMcb::create($payload);
    }

}
