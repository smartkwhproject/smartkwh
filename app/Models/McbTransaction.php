<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McbTransaction extends Model
{

    protected $table = "mcb_transaction";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'datemcb', 'timemcb', 'stream', 'voltage', 'wh', 'kwh',
        'mcb_id', 'block_id', 'category_mcb_id', 'stream_reff', 'voltage_reff',
    ];

    public function createData($payload)
    {
        return McbTransaction::create($payload);
    }

    public function mcbCategory()
    {
        return $this->hasOne(CategoryMcb::class, 'id', 'category_mcb_id');
    }

}
