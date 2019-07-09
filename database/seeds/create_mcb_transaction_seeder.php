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
            $dateTime       = date('Y-m-d H:i:s', strtotime("+ {$i} minute"));
            $payload        = [
                'block_id' => X, //X ubah menjadi id block
                'tanggal'  => date('Y-m-d', strtotime($dateTime)),
                'waktu'    => date('H:i:s', strtotime($dateTime)),
                'va'       => rand(0, 200),
                'vb'       => rand(0, 200),
                'vc'       => rand(0, 200),
                'vab'      => rand(0, 200),
                'vbc'      => rand(0, 200),
                'vca'      => rand(0, 200),
                'ia'       => rand(0, 200),
                'ib'       => rand(0, 200),
                'ic'       => rand(0, 200),
                'pa'       => rand(0, 200),
                'pb'       => rand(0, 200),
                'pc'       => rand(0, 200),
                'pt'       => rand(0, 200),
                'pfa'      => rand(0, 200),
                'pfb'      => rand(0, 200),
                'pfc'      => rand(0, 200),
                'ep'       => rand(0, 200),
                'eq'       => rand(0, 200),

            ];
            $mcbTransaction->createData($payload);
        }
    }
}
