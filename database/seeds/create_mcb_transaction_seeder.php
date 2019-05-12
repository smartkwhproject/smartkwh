<?php

use App\Models\Mcb_Transaction;
use Illuminate\Database\Seeder;

class create_mcb_transaction_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 31; $i++) {
            $mcbTransaction = new Mcb_Transaction();
            $payload        = [
                'datemcb'         => date('Y-m-d'),
                'timemcb'         => date('H:i:s'),
                'stream'          => rand(0, 200),
                'voltage'         => rand(0, 200),
                'kwh'             => rand(0, 200),
                'wh'              => rand(0, 200),
                'mcb_id'          => rand(1, 2),
                'block_id'        => rand(6, 8),
                'category_mcb_id' => rand(1, 3),
            ];
            $mcbTransaction->createData($payload);
        }

    }
}
