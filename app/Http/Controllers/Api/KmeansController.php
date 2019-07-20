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
        $bulan      = $request->get('bulan');
        $dataSets   = DB::select(
            DB::raw("SELECT
                        a.waktu,
                        b.gedung_id,
                        a.blok_id,
                        a.pt,
                        a.kwh,
                        '' AS pusatCluster,
                        0 AS jarakTerpendek,
                        0 AS masukKeCluster
                    FROM transaksi_mcb a
                    INNER JOIN blok b ON a.blok_id = b.id
                    INNER JOIN gedung c ON b.gedung_id = c.id
                    WHERE MONTH(a.tanggal) = {$bulan}"
            )
        );

        do {
            array_push($hasilAkhir, $this->loopingIterasi($dataSets));
            $this->jumlahIterasi += 1;
        } while (!$this->selesaiIterasi);

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

        $hasilAkhir = array(
            'dataSets'        => $dataSets,
            'pusatCluster'    => $pusatCluster,
            'hasilIterasi'    => $hasilIterasi,
            'groupingCluster' => $pusatClusterTambahan,
        );

        return $hasilAkhir;
    }

    public function pusatCluster($dataSet)
    {
        $totalDataSet = count($dataSet);
        $rand0        = rand(0, $totalDataSet);
        $rand1        = rand(0, $totalDataSet);
        $rand2        = rand(0, $totalDataSet);

        if (($rand0 != $rand1) && ($rand0 != $rand2) && ($rand1 != $rand2)) {
            $cluster0 = $this->jumlahIterasi == 1 ? $dataSet[$rand0] : $this->penampungCluster[0];
            $cluster1 = $this->jumlahIterasi == 1 ? $dataSet[$rand1] : $this->penampungCluster[1];
            $cluster2 = $this->jumlahIterasi == 1 ? $dataSet[$rand2] : $this->penampungCluster[2];
        } else {
            $rand1 = rand(0, $totalDataSet);
            $rand2 = rand(0, $totalDataSet);
        }

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

    // public function generateSentroids($dataSets)
    // {
    //     $min = 0;
    //     $max = count($dataSets) - 1;

    //     $sentroids = array();

    //     // for ($i = 0; $i < 3; $i++) {
    //     //     $random = rand($min, $max);
    //     //     array_push($sentroids, $dataSets[$random]);
    //     // }

    //     array_push($sentroids, $dataSets[0]);
    //     array_push($sentroids, $dataSets[2]);
    //     array_push($sentroids, $dataSets[4]);

    //     return $sentroids;

    // }

    // public function clusteringKmean($sentroidDatasets)
    // {
    //     $sentroids  = $sentroidDatasets['sentroids'];
    //     $sentroids1 = $sentroids[0]['cluster'];
    //     $sentroids2 = $sentroids[1]['cluster'];
    //     $sentroids3 = $sentroids[2]['cluster'];
    //     $temp       = 0;
    //     $temp1      = 0;
    //     $final      = array();

    //     for ($i = 0; $i < 5; $i++) {
    //         if ($sentroids1[$i] < $sentroids2[$i]) {
    //             $temp = $sentroids1[$i];
    //         } elseif ($sentroids1[$i] > $sentroids2[$i]) {
    //             $temp = $sentroids2[$i];
    //         }

    //         if ($temp < $sentroids3[$i]) {
    //             $temp1 = $temp;
    //         } else {
    //             $temp1 = $sentroids3[$i];
    //         }

    //         array_push($final, $temp1);
    //     }

    //     return $final;
    // }

    // public function kmeans()
    // {
    //     $dataSets  = $this->dataSetKMean();
    //     $sentroids = $this->generateSentroids($dataSets);
    //     $cluster   = array();
    //     // karena data sentroid sudah pasti 3
    //     for ($i = 0; $i < 3; $i++) {
    //         $ctrDatasets              = count($dataSets);
    //         $sentroids[$i]['cluster'] = array();
    //         for ($j = 0; $j < $ctrDatasets; $j++) {
    //             $mutationKwh  = pow($dataSets[$j]['kwh'] - $sentroids[$i]['kwh'], 2);
    //             $mutationNo   = pow($dataSets[$j]['no'] - $sentroids[$i]['no'], 2);
    //             $mutationSum  = $mutationKwh + $mutationNo;
    //             $mutationSqrt = sqrt($mutationSum);
    //             // array_push($sentroids[$i]['cluster'], array(
    //             //     'sentroidsKwh'  => $mutationKwh,
    //             //     'sentroidsNo'   => $mutationNo,
    //             //     'sentroidsSum'  => $mutationSum,
    //             //     'sentroidsSqrt' => $mutationSqrt,
    //             // ));
    //             array_push($sentroids[$i]['cluster'], $mutationSqrt);
    //         }
    //     }

    //     $sentroidDatasets = array('dataSets' => $dataSets, 'sentroids' => $sentroids);

    //     $final = $this->clusteringKmean($sentroidDatasets);

    //     return $final;
    // }

}
