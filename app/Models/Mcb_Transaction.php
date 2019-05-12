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
        'datemcb', 'timemcb', 'stream', 'voltage', 'wh', 'kwh',
        'mcb_id', 'block_id', 'category_mcb_id',
    ];

    public function createData($payload)
    {
        return Mcb_Transaction::create($payload);
    }

    public function mcbCategory()
    {
        return $this->hasOne(Category_Mcb::class, 'id', 'category_mcb_id');
    }

}
