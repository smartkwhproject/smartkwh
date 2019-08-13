<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiMcb;
use DB;
use Illuminate\Http\Request;

class KmeansController extends Controller
{
    private $transaksiMcb;
    private $jumlahIterasi          = 1;
    private $penampungCluster       = [];
    private $penampungLokasiCluster = [];
    private $selesaiIterasi         = false;
    private $lokasiCluster          = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TransaksiMcb $transaksiMcb)
    {
        $this->transaksiMcb = $transaksiMcb;
    }

    public function dataSetKMean(Request $request)
    {
        $hasilAkhir = array();

        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $gedung  = $request->get('gedung', 0);
        $refresh = $request->get('refresh', 0);

        $query = "SELECT hasil, start_date, end_date
                FROM tmp_final
                WHERE start_date = '{$startDate}'
                AND end_date = '{$endDate}'
                AND gedung_id = {$gedung}";

        $query .= ' ORDER BY id DESC LIMIT 1';

        $select = DB::select(DB::raw($query));

        // check parameter if data need to be refreshed
        // if data is not going to be refrehed then check
        // if data is already exist in db

        if (!$refresh) {
            if (count($select) > 0) {
                return response()->json(json_decode($select[0]->hasil));
            }
        }

        $query = "SELECT
                    a.tanggal,
                    a.waktu,
                    a.blok_id,
                    a.pt,
                    a.kwh,
                    b.gedung_id,
                    c.nama_gedung,
                    b.nama_blok,
                    '' AS pusatCluster,
                    0 AS jarakTerpendek,
                    0 AS masukKeCluster,
                    0 AS hasilIterasi
                FROM transaksi_mcb a
                INNER JOIN blok b ON a.blok_id = b.id
                INNER JOIN gedung c ON b.gedung_id = c.id
                WHERE a.tanggal BETWEEN '{$startDate}' AND '{$endDate}'
                ";

        if ($gedung != 0) {
            $query .= " AND b.gedung_id = {$gedung}";
        }

        $query .= " ORDER BY a.id ASC ";

        $dataSets = DB::select(DB::raw($query));

        if (count($dataSets) === 0) {
            return response()->json(array('message' => 'Data Tidak Ditemukan'), 404);
        }

        do {
            array_push($hasilAkhir, $this->loopingIterasi($dataSets));
            $this->jumlahIterasi += 1;
        } while (!$this->selesaiIterasi);

        // if need to refresh database
        if ($refresh == '1') {
            DB::table('tmp_final')
                ->where('start_date', $startDate)
                ->where('end_date', $endDate)
                ->where('gedung_id', $gedung)
                ->delete();
        }
        
        // $response = array();
        // foreach ($hasilAkhir as $value) {
        //     if ($value) {
        //         array_push($response, $value);
        //     }
        // }

        DB::insert(
            'insert into tmp_final (hasil, start_date, end_date, gedung_id) values (?, ?, ?, ?)',
            [json_encode($hasilAkhir), $startDate, $endDate, $gedung]
        );

        return response()->json($hasilAkhir);
    }

    public function loopingIterasi($dataSets)
    {
        $totalPenampungLokasi = count($this->penampungLokasiCluster);
        $pusatCluster         = $this->pusatCluster($dataSets);
        $jarak                = $this->perhitunganJarak($dataSets, $pusatCluster);
        $hasilIterasi         = $this->masukKeCluster($jarak);
        $tampilSepertiTabel   = $this->tampilDataSepertiTabel($dataSets, $hasilIterasi, $jarak);
        $pusatClusterTambahan = $this->pusatClusterTambahan($tampilSepertiTabel);

        if ($totalPenampungLokasi > 0) {
            $tempJumlahPenampungLokasi = 0;
            for ($i = 0; $i < $totalPenampungLokasi; $i++) {
                if ($this->penampungLokasiCluster[$i]['lokasiCluster'] == $hasilIterasi[$i]['lokasiCluster']) {
                    $tempJumlahPenampungLokasi += 1;
                }
            }

            if ($tempJumlahPenampungLokasi == $totalPenampungLokasi) {
                $this->selesaiIterasi = true;
            }
        }

        //tampilan yang diberikan...//
        $hasilAkhir = array(
            'dataSets'        => $dataSets,
            'pusatCluster'    => $pusatCluster,
            'hasilIterasi'    => $hasilIterasi,
            'groupingCluster' => $pusatClusterTambahan,
        );
        
        // if($this->selesaiIterasi) {
        //     $hasilAkhir = null;    
        // }

        return $hasilAkhir;
    }

    public function pusatCluster($dataSet)
    {
        $totalDataSet = count($dataSet);
        // $rand0        = abs(rand(0, $totalDataSet / 3));
        // $rand1        = abs(rand($totalDataSet / 3, ($totalDataSet * 2) / 3));
        // $rand2        = abs(rand(($totalDataSet * 2) / 3, $totalDataSet));

        // $masihSama = true;
        // while ($masihSama) {
        //     $rand0 = rand(0, $totalDataSet);
        //     $rand1 = rand(0, $totalDataSet);
        //     $rand2 = rand(0, $totalDataSet);

        //     $masihSama = false;

        //     if ($rand0 == $rand1) {
        //         $masihSama = true;
        //     }

        //     if ($rand1 == $rand2) {
        //         $masihSama = true;
        //     }

        //     if ($rand0 == $rand2) {
        //         $masihSama = true;
        //     }
        // }

        // $cluster0 = $this->jumlahIterasi == 1 ? $dataSet[$rand0] : $this->penampungCluster[0];
        // $cluster1 = $this->jumlahIterasi == 1 ? $dataSet[$rand1] : $this->penampungCluster[1];
        // $cluster2 = $this->jumlahIterasi == 1 ? $dataSet[$rand2] : $this->penampungCluster[2];
        // $cluster0 = $this->jumlahIterasi == 1 ? $dataSet[12] : $this->penampungCluster[0];
        // $cluster1 = $this->jumlahIterasi == 1 ? $dataSet[4] : $this->penampungCluster[1];
        // $cluster2 = $this->jumlahIterasi == 1 ? $dataSet[26] : $this->penampungCluster[2];

        //mulai--------------------------------------------------------------------------------------------------------------
        $nilaiMin = 0;
        $nilaiMax = 0;
        for ($i = 0; $i < $totalDataSet; $i++) {
            if ($i == 0) {
                $nilaiMin = $dataSet[$i]->kwh;
            } else if ($i > 0) {
                if ($dataSet[$i]->kwh <= $nilaiMin) {
                    $nilaiMin = $dataSet[$i]->kwh;
                } else {
                    $nilaiMin = $nilaiMin;
                }
            }
        }

        // for ($k=0; $k < $totalDataSet; $k++) {
        //     if ($k == 0) {
        //         $nilaiMax = $dataSet[$k]->kwh;
        //     }
        //     else if ($k > 0) {
        //         if ($dataSet[$k]->kwh >= $nilaiMax) {
        //             $nilaiMax = $dataSet[$k]->kwh;
        //         } else {
        //             $nilaiMax = $nilaiMax;
        //         }
        //     }
        // }

        for ($i = 0; $i < $totalDataSet; $i++) {
            if ($i == 0) {
                $nilaiMax = $dataSet[$i]->kwh;
            } else if ($i > 0) {
                if ($dataSet[$i]->kwh >= $nilaiMax) {
                    $nilaiMax = $dataSet[$i]->kwh;
                } else {
                    $nilaiMax = $nilaiMax;
                }
            }
        }
        $selisihNilai = $nilaiMax - $nilaiMin;
        $range        = ($selisihNilai) / 3;
        $range2       = $nilaiMin + $range;
        $range1       = $range2 + $range;
        $range0       = $range1 + $range;

        $random0 = array();
        $random1 = array();
        $random2 = array();
        for ($j = 0; $j < $totalDataSet; $j++) {
            if ($dataSet[$j]->kwh <= $range2) {
                $random2[] = $dataSet[$j];
            }
            if (($dataSet[$j]->kwh >= $range2) && ($dataSet[$j]->kwh <= $range1)) {
                $random1[] = $dataSet[$j];
            }
            if (($dataSet[$j]->kwh >= $range1) && ($dataSet[$j]->kwh <= $range0)) {
                $random0[] = $dataSet[$j];
            }

        }
        // dd($random0);

        $totalRandom0 = count($random0) - 1;
        $totalRandom1 = count($random1) - 1;
        $totalRandom2 = count($random2) - 1;

        $rand0 = rand(0, $totalRandom0);
        $rand1 = rand(0, $totalRandom1);
        $rand2 = rand(0, $totalRandom2);

        $cluster0 = $this->jumlahIterasi == 1 ? $random0[$rand0] : $this->penampungCluster[0];
        $cluster1 = $this->jumlahIterasi == 1 ? $random1[$rand1] : $this->penampungCluster[1];
        $cluster2 = $this->jumlahIterasi == 1 ? $random2[$rand2] : $this->penampungCluster[2];
        // dd($random1[$rand1]);
        // selesai--------------------------------------------------------------------------------------------------------------

        $kumpulanCluster = array(
            $cluster0,
            $cluster1,
            $cluster2,
        );

        return $kumpulanCluster;
    }

    public function perhitunganJarak($dataSet, $pusatCluster)
    {
        $totalDataset = count($dataSet);
        $totalCluster = count($pusatCluster);

        $jarak = array();

        for ($i = 0; $i < $totalDataset; $i++) {
            for ($j = 0; $j < $totalCluster; $j++) {
                $tempKwh       = pow(abs($dataSet[$i]->kwh - $pusatCluster[$j]->kwh), 2);
                $tempPt        = pow(abs($dataSet[$i]->pt - $pusatCluster[$j]->pt), 2);
                $temp          = sqrt($tempKwh + $tempPt);
                $jarak[$i][$j] = $temp;
            }
        }

        return $jarak;
    }

    public function masukKeCluster($jarak)
    {
        $this->penampungLokasiCluster = $this->lokasiCluster;

        $totalJarak     = count($jarak);
        $nilaiTerpendek = 0;
        $pembanding     = 0;
        for ($i = 0; $i < $totalJarak; $i++) {
            $temp = $jarak[$i];
            if (($temp[0] > $temp[1]) || ($temp[0] == $temp[1])) {
                $pembanding                               = $temp[1];
                $this->lokasiCluster[$i]['lokasiCluster'] = 1;
            } elseif ($temp[0] < $temp[1]) {
                $pembanding                               = $temp[0];
                $this->lokasiCluster[$i]['lokasiCluster'] = 0;
            }

            if ($pembanding > $temp[2]) {
                $nilaiTerpendek                           = $temp[2];
                $this->lokasiCluster[$i]['lokasiCluster'] = 2;
            } else {
                $nilaiTerpendek = $pembanding;
            }

            $this->lokasiCluster[$i]['nilaiTerpendek'] = $nilaiTerpendek;
            // foreach ($temp as $key => $value) {
            //     if ($nilaiTerpendek == $value) {
            //         $this->lokasiCluster[$i]['lokasiCluster'] = $key;
            //     }
            // }
        }

        return $this->lokasiCluster;
    }

    public function tampilDataSepertiTabel($dataSet, $nilaiTerendahDariJarak, $perhitunganJarak)
    {
        $totalDataSet = count($dataSet);
        // return $nilaiTerendahDariJarak;
        for ($i = 0; $i < $totalDataSet; $i++) {
            $dataSet[$i]->jarakTerpendek = $nilaiTerendahDariJarak[$i]['nilaiTerpendek'];
            $dataSet[$i]->masukKeCluster = $nilaiTerendahDariJarak[$i]['lokasiCluster'];
            $dataSet[$i]->pusatCluster   = $perhitunganJarak[$i];
        }

        return $dataSet;
    }

    public function pusatClusterTambahan($dataSet)
    {
        $this->penampungCluster = array();
        $mainGroup              = array();
        foreach ($dataSet as $val) {
            array_push($mainGroup, $val->masukKeCluster);
        }
        $mainGroup = array_values(array_unique($mainGroup));
        sort($mainGroup);
        for ($i = 0; $i < count($mainGroup); $i++) {
            $jumlahDataDalamGroup = 0;
            $jumlahPt             = 0;
            $jumlahKwh            = 0;
            $rata2Kwh             = 0;
            $rata2Pt              = 0;
            for ($j = 0; $j < count($dataSet); $j++) {
                if ($dataSet[$j]->masukKeCluster == $mainGroup[$i]) {
                    $jumlahDataDalamGroup += 1;
                    $jumlahKwh += $dataSet[$j]->kwh;
                    $jumlahPt += $dataSet[$j]->pt;
                }
            }
            $rata2Kwh = $jumlahKwh / $jumlahDataDalamGroup;
            $rata2Pt  = $jumlahPt / $jumlahDataDalamGroup;
            array_push($this->penampungCluster, (object) array(
                'pt'  => $rata2Pt,
                'kwh' => $rata2Kwh,
            ));
        }

        return $this->penampungCluster;
    }
}
