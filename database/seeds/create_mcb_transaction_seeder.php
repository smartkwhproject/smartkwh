<?php

use App\Models\TransaksiMcb;
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
            $mcbTransaction = new TransaksiMcb();
            $dateTime       = date('Y-m-d H:i:s', strtotime("+ {$i} minute"));
            $payload        = [
                'blok_id' => 1,
                'tanggal' => date('Y-m-d', strtotime($dateTime)),
                'waktu'   => date('H:i:s', strtotime($dateTime)),
                'va'      => rand(220, 233),
                'vb'      => rand(220, 233),
                'vc'      => rand(220, 233),
                'vab'     => rand(330, 402),
                'vbc'     => rand(330, 402),
                'vca'     => rand(330, 402),
                'ia'      => rand(0, 2),
                'ib'      => rand(0, 1),
                'ic'      => rand(0, 2),
                'pa'      => rand(0, 5),
                'pb'      => rand(0, 5),
                'pc'      => rand(0, 5),
                'pt'      => rand(0, 5),
                'pfa'     => rand(0, 5),
                'pfb'     => rand(0, 5),
                'pfc'     => rand(0, 5),
                'ep'      => rand(0, 5),
                'eq'      => rand(0, 3),

            ];
            $mcbTransaction->createData($payload);
        }
        for ($i = 0; $i < 10; $i++) {
            $mcbTransaction = new TransaksiMcb();
            $dateTime       = date('Y-m-d H:i:s', strtotime("+ {$i} minute"));
            $payload        = [
                'blok_id' => 2,
                'tanggal' => date('Y-m-d', strtotime($dateTime)),
                'waktu'   => date('H:i:s', strtotime($dateTime)),
                'va'      => rand(220, 233),
                'vb'      => rand(220, 233),
                'vc'      => rand(220, 233),
                'vab'     => rand(330, 402),
                'vbc'     => rand(330, 402),
                'vca'     => rand(330, 402),
                'ia'      => rand(0, 2),
                'ib'      => rand(0, 1),
                'ic'      => rand(0, 2),
                'pa'      => rand(0, 5),
                'pb'      => rand(0, 5),
                'pc'      => rand(0, 5),
                'pt'      => rand(0, 5),
                'pfa'     => rand(0, 5),
                'pfb'     => rand(0, 5),
                'pfc'     => rand(0, 5),
                'ep'      => rand(0, 5),
                'eq'      => rand(0, 3),

            ];
            $mcbTransaction->createData($payload);
        }
        for ($i = 0; $i < 10; $i++) {
            $mcbTransaction = new TransaksiMcb();
            $dateTime       = date('Y-m-d H:i:s', strtotime("+ {$i} minute"));
            $payload        = [
                'blok_id' => 3,
                'tanggal' => date('Y-m-d', strtotime($dateTime)),
                'waktu'   => date('H:i:s', strtotime($dateTime)),
                'va'      => rand(220, 233),
                'vb'      => rand(220, 233),
                'vc'      => rand(220, 233),
                'vab'     => rand(330, 402),
                'vbc'     => rand(330, 402),
                'vca'     => rand(330, 402),
                'ia'      => rand(0, 2),
                'ib'      => rand(0, 1),
                'ic'      => rand(0, 2),
                'pa'      => rand(0, 5),
                'pb'      => rand(0, 5),
                'pc'      => rand(0, 5),
                'pt'      => rand(0, 5),
                'pfa'     => rand(0, 5),
                'pfb'     => rand(0, 5),
                'pfc'     => rand(0, 5),
                'ep'      => rand(0, 5),
                'eq'      => rand(0, 3),

            ];
            $mcbTransaction->createData($payload);
        }
    }
}
