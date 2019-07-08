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
        for ($i = 0; $i < 41000; $i++) {
            $mcbTransaction = new McbTransaction();
            $dateTime       = date('Y-m-d H:i:s', strtotime("+ {$i} minute"));
            $payload        = [
                'datemcb'         => date('Y-m-d', strtotime($dateTime)),
                'timemcb'         => date('H:i:s', strtotime($dateTime)),
                'stream'          => rand(0, 200),
                'voltage'         => rand(0, 200),
                'kwh'             => rand(0, 200),
                'wh'              => rand(0, 200),
                'mcb_id'          => 1,
                'block_id'        => 2,
                'category_mcb_id' => rand(1, 4),
                'stream_reff'     => rand(0, 200),
                'voltage_reff'    => rand(0, 200),
            ];
            $mcbTransaction->createData($payload);
        }
	for ($i = 0; $i < 41000; $i++) {
            $mcbTransaction = new McbTransaction();
            $dateTime       = date('Y-m-d H:i:s', strtotime("+ {$i} minute"));
            $payload        = [
                'datemcb'         => date('Y-m-d', strtotime($dateTime)),
                'timemcb'         => date('H:i:s', strtotime($dateTime)),
                'stream'          => rand(0, 200),
                'voltage'         => rand(0, 200),
                'kwh'             => rand(0, 200),
                'wh'              => rand(0, 200),
                'mcb_id'          => 2,
                'block_id'        => 3,
                'category_mcb_id' => rand(1, 4),
                'stream_reff'     => rand(0, 200),
                'voltage_reff'    => rand(0, 200),
            ];
            $mcbTransaction->createData($payload);
        }
	for ($i = 0; $i < 41000; $i++) {
            $mcbTransaction = new McbTransaction();
            $dateTime       = date('Y-m-d H:i:s', strtotime("+ {$i} minute"));
            $payload        = [
                'datemcb'         => date('Y-m-d', strtotime($dateTime)),
                'timemcb'         => date('H:i:s', strtotime($dateTime)),
                'stream'          => rand(0, 200),
                'voltage'         => rand(0, 200),
                'kwh'             => rand(0, 200),
                'wh'              => rand(0, 200),
                'mcb_id'          => 3,
                'block_id'        => 4,
                'category_mcb_id' => rand(1, 4),
                'stream_reff'     => rand(0, 200),
                'voltage_reff'    => rand(0, 200),
            ];
            $mcbTransaction->createData($payload);
        }

    }
}
