<?php

use App\Models\TransaksiMcb;
use Carbon\Carbon;
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
        $currentMinutes = 0;
        for ($i = 0; $i < 10; $i++) {
            $j              = 5;
            $menit          = $i * $j;
            $mcbTransaction = new TransaksiMcb();
            //$dateTime       = date('Y-m-d H:i:s', strtotime("+ {$menit} minute"));
            $date = Carbon::parse('2019-07-01');
            $date->addMinutes($currentMinutes);
            $payload = [
                'blok_id' => 4,
                //'tanggal' => date('Y-m-d', strtotime($dateTime)),
                'tanggal' => $date->format('Y-m-d'),
                //'waktu'   => date('H:i:s', strtotime($dateTime)),
                'waktu'   => $date->format('H:i:s'),
                'va'      => rand(232, 234),
                'vb'      => rand(232, 234),
                'vc'      => rand(232, 234),
                'vab'     => rand(400, 402),
                'vbc'     => rand(400, 402),
                'vca'     => rand(400, 402),
                'ia'      => rand(1, 5) / 5,
                'ib'      => rand(1, 5) / 5,
                'ic'      => rand(1, 5) / 5,
                'pa'      => rand(1, 5) / 5,
                'pb'      => rand(1, 5) / 5,
                'pc'      => rand(1, 5) / 5,
                'pt'      => rand(1, 5) / 5,
                'pfa'     => rand(1, 5) / 5,
                'pfb'     => rand(1, 5) / 5,
                'pfc'     => rand(1, 5) / 5,
                'ep'      => rand(1, 5) / 5,
                'eq'      => rand(1, 5) / 5,
                'ep'      => (rand(1, 5) * 5),
                'kwh'     => rand(1, 5),
            ];
            $mcbTransaction->createData($payload);
            $currentMinutes += 5;
        }
        // for ($i = 0; $i < 80640; $i++) {
        //     $j              = 5;
        //     $menit          = $i * $j;
        //     $mcbTransaction = new TransaksiMcb();
        //     //$dateTime       = date('Y-m-d H:i:s', strtotime("+ {$menit} minute"));
        //     $date = Carbon::parse('2019-06-01');
        //     $date->addMinutes($currentMinutes);
        //     $payload = [
        //         'blok_id' => 5,
        //         //'tanggal' => date('Y-m-d', strtotime($dateTime)),
        //         'tanggal' => $date->format('Y-m-d'),
        //         //'waktu'   => date('H:i:s', strtotime($dateTime)),
        //         'waktu'   => $date->format('H:i:s'),
        //         'va'      => rand(232, 234),
        //         'vb'      => rand(232, 234),
        //         'vc'      => rand(232, 234),
        //         'vab'     => rand(400, 402),
        //         'vbc'     => rand(400, 402),
        //         'vca'     => rand(400, 402),
        //         'ia'      => rand(1, 5) / 5,
        //         'ib'      => rand(1, 5) / 5,
        //         'ic'      => rand(1, 5) / 5,
        //         'pa'      => rand(1, 5) / 5,
        //         'pb'      => rand(1, 5) / 5,
        //         'pc'      => rand(1, 5) / 5,
        //         'pt'      => rand(1, 5) / 5,
        //         'pfa'     => rand(1, 5) / 5,
        //         'pfb'     => rand(1, 5) / 5,
        //         'pfc'     => rand(1, 5) / 5,
        //         'ep'      => rand(1, 5) / 5,
        //         'eq'      => rand(1, 5) / 5,
        //         'ep'      => (rand(1, 5) * 5),
        //         'kwh'     => rand(1, 5),
        //     ];
        //     $mcbTransaction->createData($payload);
        //     $currentMinutes += 5;
        // }
        // for ($i = 0; $i < 80640; $i++) {
        //     $j              = 5;
        //     $menit          = $i * $j;
        //     $mcbTransaction = new TransaksiMcb();
        //     //$dateTime       = date('Y-m-d H:i:s', strtotime("+ {$menit} minute"));
        //     $date = Carbon::parse('2019-06-01');
        //     $date->addMinutes($currentMinutes);
        //     $payload = [
        //         'blok_id' => 6,
        //         //'tanggal' => date('Y-m-d', strtotime($dateTime)),
        //         'tanggal' => $date->format('Y-m-d'),
        //         //'waktu'   => date('H:i:s', strtotime($dateTime)),
        //         'waktu'   => $date->format('H:i:s'),
        //         'va'      => rand(232, 234),
        //         'vb'      => rand(232, 234),
        //         'vc'      => rand(232, 234),
        //         'vab'     => rand(400, 402),
        //         'vbc'     => rand(400, 402),
        //         'vca'     => rand(400, 402),
        //         'ia'      => rand(1, 5) / 5,
        //         'ib'      => rand(1, 5) / 5,
        //         'ic'      => rand(1, 5) / 5,
        //         'pa'      => rand(1, 5) / 5,
        //         'pb'      => rand(1, 5) / 5,
        //         'pc'      => rand(1, 5) / 5,
        //         'pt'      => rand(1, 5) / 5,
        //         'pfa'     => rand(1, 5) / 5,
        //         'pfb'     => rand(1, 5) / 5,
        //         'pfc'     => rand(1, 5) / 5,
        //         'ep'      => rand(1, 5) / 5,
        //         'eq'      => rand(1, 5) / 5,
        //         'ep'      => (rand(1, 5) * 5),
        //         'kwh'     => rand(1, 5),
        //     ];
        //     $mcbTransaction->createData($payload);
        //     $currentMinutes += 5;
        // }
    }
}
