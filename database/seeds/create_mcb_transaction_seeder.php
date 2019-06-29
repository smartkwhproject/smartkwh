<?php

use App\Models\McbTransaction;
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
        for ($i = 0; $i < 10; $i++) {
            $mcbTransaction = new McbTransaction();
            $payload        = [
                'datemcb'         => date('Y-m-d'),
                'timemcb'         => date('H:i:s'),
                'stream'          => rand(0, 200),
                'voltage'         => rand(0, 200),
                'kwh'             => rand(0, 200),
                'wh'              => rand(0, 200),
                'mcb_id'          => rand(1, 5),
                'block_id'        => rand(1, 5),
                'category_mcb_id' => rand(1, 4),
                'stream_reff'     => rand(0, 200),
                'voltage_reff'    => rand(0, 200),
            ];
            $mcbTransaction->createData($payload);
        }

    }
}
