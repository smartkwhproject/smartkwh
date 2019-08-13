<?php

use App\Models\TransaksiMcb;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class create_mcb_transaction_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Request $request)
    {
        $loop           = 0;
        $currentMinutes = 0;
        if ($loop == 0) {
            for ($i = 0; $i < 24; $i++) {
                $j              = 5;
                $menit          = $i * $j;
                $mcbTransaction = new TransaksiMcb();
                //$dateTime       = date('Y-m-d H:i:s', strtotime("+ {$menit} minute"));
                $date = Carbon::parse('2019-07-01');
                //$date = Carbon::parse('12:00:00');
                $date->addMinutes($currentMinutes);
                $payload = [
                    'blok_id' => 39,
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

                    'pt'      => rand(77, 79) / 10,

                    'pfa'     => 1,
                    'pfb'     => 1,
                    'pfc'     => 1,
                    'ep'      => rand(1, 5) / 5,
                    'eq'      => rand(1, 5) / 5,
                    'ep'      => (rand(1, 5) * 5),

                    'kwh'     => 0,
                ];
                $mcbTransaction->createData($payload);
                $currentMinutes += 10;
                if ($i == 23) {
                    $loop = 1;
                }
            }
        }
        if ($loop == 1) {
            for ($i = 0; $i < 24; $i++) {
                $j              = 5;
                $menit          = $i * $j;
                $mcbTransaction = new TransaksiMcb();
                //$dateTime       = date('Y-m-d H:i:s', strtotime("+ {$menit} minute"));
                $date = Carbon::parse('2019-07-01');
                //$date = Carbon::parse('12:00:00');
                $date->addMinutes($currentMinutes);
                $payload = [
                    'blok_id' => 39,
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

                    'pt'      => rand(153, 156) / 10,

                    'pfa'     => 1,
                    'pfb'     => 1,
                    'pfc'     => 1,
                    'ep'      => rand(1, 5) / 5,
                    'eq'      => rand(1, 5) / 5,
                    'ep'      => (rand(1, 5) * 5),

                    'kwh'     => 0,
                ];
                $mcbTransaction->createData($payload);
                $currentMinutes += 10;
                if ($i == 23) {
                    $loop = 2;
                }
            }
        }
        if ($loop == 2) {
            for ($i = 0; $i < 24; $i++) {
                $j              = 5;
                $menit          = $i * $j;
                $mcbTransaction = new TransaksiMcb();
                //$dateTime       = date('Y-m-d H:i:s', strtotime("+ {$menit} minute"));
                $date = Carbon::parse('2019-07-01');
                //$date = Carbon::parse('12:00:00');
                $date->addMinutes($currentMinutes);
                $payload = [
                    'blok_id' => 39,
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

                    'pt'      => rand(386, 389) / 10,

                    'pfa'     => 1,
                    'pfb'     => 1,
                    'pfc'     => 1,
                    'ep'      => rand(1, 5) / 5,
                    'eq'      => rand(1, 5) / 5,
                    'ep'      => (rand(1, 5) * 5),

                    'kwh'     => 0,
                ];
                $mcbTransaction->createData($payload);
                $currentMinutes += 10;
                if ($i == 23) {
                    $loop = 3;
                }
            }
        }
        if ($loop == 3) {
            for ($i = 0; $i < 24; $i++) {
                $j              = 5;
                $menit          = $i * $j;
                $mcbTransaction = new TransaksiMcb();
                //$dateTime       = date('Y-m-d H:i:s', strtotime("+ {$menit} minute"));
                $date = Carbon::parse('2019-07-01');
                //$date = Carbon::parse('12:00:00');
                $date->addMinutes($currentMinutes);
                $payload = [
                    'blok_id' => 39,
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

                    'pt'      => rand(462, 465) / 10,

                    'pfa'     => 1,
                    'pfb'     => 1,
                    'pfc'     => 1,
                    'ep'      => rand(1, 5) / 5,
                    'eq'      => rand(1, 5) / 5,
                    'ep'      => (rand(1, 5) * 5),

                    'kwh'     => 0,
                ];
                $mcbTransaction->createData($payload);
                $currentMinutes += 10;
                if ($i == 23) {
                    $loop = 4;
                }
            }
        }
        if ($loop == 4) {
            for ($i = 0; $i < 24; $i++) {
                $j              = 5;
                $menit          = $i * $j;
                $mcbTransaction = new TransaksiMcb();
                //$dateTime       = date('Y-m-d H:i:s', strtotime("+ {$menit} minute"));
                $date = Carbon::parse('2019-07-01');
                //$date = Carbon::parse('12:00:00');
                $date->addMinutes($currentMinutes);
                $payload = [
                    'blok_id' => 39,
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

                    'pt'      => rand(308, 310) / 10,

                    'pfa'     => 1,
                    'pfb'     => 1,
                    'pfc'     => 1,
                    'ep'      => rand(1, 5) / 5,
                    'eq'      => rand(1, 5) / 5,
                    'ep'      => (rand(1, 5) * 5),

                    'kwh'     => 0,
                ];
                $mcbTransaction->createData($payload);
                $currentMinutes += 10;
                if ($i == 23) {
                    $loop = 5;
                }
            }
        }
        if ($loop == 5) {
            for ($i = 0; $i < 24; $i++) {
                $j              = 5;
                $menit          = $i * $j;
                $mcbTransaction = new TransaksiMcb();
                //$dateTime       = date('Y-m-d H:i:s', strtotime("+ {$menit} minute"));
                $date = Carbon::parse('2019-07-01');
                //$date = Carbon::parse('12:00:00');
                $date->addMinutes($currentMinutes);
                $payload = [
                    'blok_id' => 39,
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

                    'pt'      => rand(231, 233) / 10,

                    'pfa'     => 1,
                    'pfb'     => 1,
                    'pfc'     => 1,
                    'ep'      => rand(1, 5) / 5,
                    'eq'      => rand(1, 5) / 5,
                    'ep'      => (rand(1, 5) * 5),

                    'kwh'     => 0,
                ];
                $mcbTransaction->createData($payload);
                $currentMinutes += 10;
                if ($i == 23) {
                    $loop = 6;
                }
            }
        }
    }
}
