<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blok;
use App\Models\Gedung;
use App\Models\TransaksiMcb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $stats = DB::select(DB::raw(
            'SELECT a.tanggal, a.waktu,
                    a.va, a.vb, a.vc, a.vab, a.vbc, a.vca,
                    a.ia, a.ib, a.ic,
                    a.pa, a.pb, a.pc, a.pt,
                    a.pfa, a.pfb, a.pfc,
                    a.ep, a.eq,
                    b.nama_blok,
                    c.nama_gedung
            FROM transaksi_mcb a
            JOIN blok b on (a.blok_id = b.id)
            JOIN gedung c on (b.gedung_id = c.id)'
        ));
        return $stats;
    }
    // public function history(Request $request)
    // {
    //     $startDate = date('Y-m-d');
    //     if ($request->has('tanggal')) {
    //         $startDate = $request->get('tanggal');
    //     }
    //     $stats = DB::select(DB::raw(
    //         'SELECT a.tanggal, a.waktu,
    //                 a.va, a.vb, a.vc, a.vab, a.vbc, a.vca,
    //                 a.ia, a.ib, a.ic,
    //                 a.pa, a.pb, a.pc, a.pt,
    //                 a.pfa, a.pfb, a.pfc,
    //                 a.ep, a.eq,
    //                 b.nama_blok,
    //                 c.nama_gedung
    //         FROM transaksi_mcb a
    //         JOIN blok b on (a.blok_id = b.id)
    //         JOIN gedung c on (b.gedung_id = c.id)
    //         WHERE a.tanggal = ?'
    //     ), [$startDate]);
    //     return $stats;
    // }

    // public function history(Request $request)
    // {
    //     $building = new Gedung();
    //     $response = $building->select('id', 'nama_gedung')->get();
    //     $counting = 0;
    //     foreach ($response as $data) {
    //         $comments = Blok::select('id', 'nama_blok', 'deskripsi')->where('gedung_id', $data->id)->get();
    //         foreach ($comments as $datamcb) {
    //             $kwhTerakhir = TransaksiMcb::select('ep', 'waktu')
    //                 ->orderBy('tanggal', 'DESC')
    //                 ->orderBy('waktu', 'DESC')
    //                 ->where('blok_id', $datamcb->id)
    //                 ->first();
    //             $datamcb->waktu = isset($kwhTerakhir->waktu) ? $kwhTerakhir->waktu : 0;
    //             $datamcb->kwh = isset($kwhTerakhir->ep) ? $kwhTerakhir->ep : 0;
    //             $datamcb->ep  = isset($kwhTerakhir->ep) ? $kwhTerakhir->ep : 0;
    //         }
    //         $data->pages = $request->page;
    //         $data->blok  = $comments;
    //     }
    //     return response()->json($response, 200);
    // }

    public function history(Request $request)
    {
        $building = new Gedung();
        $blok     = new Blok();
        $response = $building->select('id', 'nama_gedung')->get();
        foreach ($response as $data) {
            $comments = Blok::select('id', 'nama_blok')->where('gedung_id', $data->id)->get();
            foreach ($comments as $datamcb) {
                $mcb             = TransaksiMcb::select('kwh', 'waktu')->where('blok_id', $datamcb->id)->where('tanggal', $request->tanggal)->get();
                $kwhTerakhir     = TransaksiMcb::select('kwh')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->where('tanggal', $request->tanggal)->first();
                $kwhSblmTerakhir = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->skip(1)->first();
                $lastUpdate = TransaksiMcb::select('tanggal')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                //ep non object
                $jmlData  = 0;
                foreach ($mcb as $datamcb2) {
                    $jmlData = $jmlData + $datamcb2['kwh'];
                }
                $datamcb->kwh    = $jmlData;
                // $datamcb->kwh = isset($kwhTerakhir->ep) ? $kwhTerakhir->ep : 0;
                //$dBlok->ep = isset($mcb->ep) ? $mcb->ep : 0;
                $datamcb->mcb = $mcb;
            }
            $data->lastUpdate = isset($lastUpdate->tanggal) ? $lastUpdate->tanggal : 0;
            $data->blok = $comments;
        }
        return response()->json($response, 200);
    }

    public function filterHistory(Request $request)
    {
        $building = new Gedung();
        $blok     = new Blok();
        $dPage = 1;
        if($request->page == 1) {
            $dPage = null; 
        } else if ($request->page == 2) {
            $dPage = $request->limit;
        } else {
            $dPage = ($request->limit * ($request->page - 1));
        }
        $response = $building->select('id', 'nama_gedung')->where('id', $request->gedung_id)->get();
        // $jmlData  = 0;
        foreach ($response as $data) {
            $comments = Blok::select('id', 'nama_blok', 'deskripsi')->where('gedung_id', $data->id)->get();
            foreach ($comments as $datamcb) {
                $mcb                 = TransaksiMcb::select('kwh', 'waktu')->where('blok_id', $datamcb->id)->where('tanggal', $request->tanggal)->offset($dPage)->limit($request->limit)->get();
                $jmlSemuaPerTgl      = TransaksiMcb::select('kwh', 'waktu')->where('blok_id', $datamcb->id)->where('tanggal', $request->tanggal)->get();
                $jml                 = TransaksiMcb::select('kwh', 'waktu')->where('blok_id', $datamcb->id)->where('tanggal', $request->tanggal)->get();
                $kwhTerakhir         = TransaksiMcb::select('kwh')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('tanggal', $request->tanggal)->where('blok_id', $datamcb->id)->first();
                $kwhSblmTerakhir     = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->skip(1)->first();
                $lastUpdate = TransaksiMcb::select('tanggal')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                // $datamcb->kwh        = $kwhTerakhir['kwh'];
                $jmlData  = 0;
                foreach ($jmlSemuaPerTgl as $datamcb2) {
                    $jmlData = $jmlData + $datamcb2['kwh'];
                }
                $datamcb->kwh        = $jmlData;
                $datamcb->jumlahData = count($jml);
                $datamcb->mcb        = $mcb;
                // if (count($jml) > 0) {
                //     $jmlData = count($jml);
                // } else if (count($jml) == 0) {
                //     $jmlData = [];
                // }
                
            }
            
            // $data->jumlahData = ceil(count($jmlData) / 10);
            // dd(count($jml));
            // dd(count($jml));
            $data->lastUpdate = isset($lastUpdate->tanggal) ? $lastUpdate->tanggal : 0;
            $data->jumlahData = $datamcb->jumlahData;
            $data->pages      = $request->page;
            $data->blok       = $comments;
        }
        return response()->json($response, 200);
    }

    public function filterTotal(Request $request)
    {
        if($request->page == 1) {
            $dPage = null; 
        } else if ($request->page == 2) {
            $dPage = $request->limit;
        } else {
            $dPage = ($request->limit * ($request->page - 1));
        }
        $building = new Gedung();
        $gedung   = $building->select('id', 'nama_gedung')->where('id', $request->gedung_id)->get();
        foreach ($gedung as $dGedung) {
            $blok = DB::table('blok')
                ->select('id', 'nama_blok')
                ->where('gedung_id', '=', $dGedung->id)
                ->get();
            foreach ($blok as $dBlok) {
                $tanggal = DB::table('transaksi_mcb')
                    ->select('tanggal')
                    ->where('blok_id', '=', $dBlok->id)
                    ->whereMonth('tanggal', '=', $request->bulan)
                    ->groupBy('tanggal')->offset($dPage)->limit($request->limit)->get();
                $jml = DB::table('transaksi_mcb')
                    ->select('tanggal')
                    ->where('blok_id', '=', $dBlok->id)
                    ->whereMonth('tanggal', '=', $request->bulan)
                    ->groupBy('tanggal')->get();
                $lastUpdate = DB::table('transaksi_mcb')
                    ->select('tanggal')
                    ->where('blok_id', $dBlok->id)
                    ->orderBy('tanggal', 'DESC')
                    ->first();
                
            }
            // $dGedung->jmlData = ceil(count($jml) / $request->limit0);
            $dGedung->jmlData = count($jml);
            $dGedung->pages = $request->page;
            $dGedung->lastUpdate = isset($lastUpdate->tanggal) ? $lastUpdate->tanggal : 0;
            $dGedung->tgl = $tanggal;
            $ctrTanggal = 0;
            foreach ($tanggal as $dDate) {
                
                $temp = 0;
                $n = 0;
                $blok = DB::table('blok')
                    ->select('id', 'nama_blok')
                    ->where('gedung_id', $dGedung->id)
                    ->get();
                $jmlSemua = 0;
                foreach ($blok as $dBlok) {
                    $mcb = DB::table('transaksi_mcb')
                        ->select('kwh')
                        ->where('blok_id', $dBlok->id)
                        ->where('tanggal', $dDate->tanggal)
                        ->orderBy('tanggal', 'ASC')
                        // ->whereMonth('tanggal', '=', $request->bulan)
                        ->get();
                    // $dBlok->kwh = isset($mcb->kwh) ? $mcb->kwh : 0;
                    // $n = isset($mcb->kwh) ? $mcb->kwh : 0;
                    // $temp = $temp + $n;
                    $jmlPerBlok = 0;
                    foreach($mcb as $dMcb){
                        $jmlPerBlok = $jmlPerBlok + $dMcb->kwh;
                    }
                    $dBlok->kwh = $jmlPerBlok;
                    $ppp = $jmlPerBlok;
                    $jmlSemua = $jmlSemua + $ppp;
                }
                $ctrTanggal++;
                $dDate->total = $jmlSemua;
                $dDate->blok = $blok;
            }
        }

        $xx = array(
            'status'  => 'success',
            'message' => 'Data Berhasil Ditampilkan',
            'data'    => $gedung,
        );
        return response()->json($xx, 200);
    }

    public function total(Request $request)
    {
        $building = new Gedung();
        $gedung   = $building->select('id', 'nama_gedung')->get();
        foreach ($gedung as $dGedung){
            $blok = DB::table('blok')
                ->select('id', 'nama_blok')
                ->where('gedung_id', $dGedung->id)
                ->get();
            foreach ($blok as $dBlok) {
                $tanggal = DB::table('transaksi_mcb')
                    ->select('tanggal')
                    ->where('blok_id', $dBlok->id)
                    ->whereMonth('tanggal', '=', $request->bulan)
                    ->groupBy('tanggal')
                    ->get();
                $lastUpdate = DB::table('transaksi_mcb')
                    ->select('tanggal')
                    ->where('blok_id', $dBlok->id)
                    ->orderBy('tanggal', 'DESC')
                    ->first();
            }
            $dGedung->lastUpdate = isset($lastUpdate->tanggal) ? $lastUpdate->tanggal : 0;
            $dGedung->tgl = $tanggal;
            $ctrTanggal = 0;
            foreach ($tanggal as $dDate) {
                
                $temp = 0;
                $n = 0;
                $blok = DB::table('blok')
                    ->select('id', 'nama_blok')
                    ->where('gedung_id', $dGedung->id)
                    ->get();
                $jmlSemua = 0;
                foreach ($blok as $dBlok) {
                    $num = DB::table('transaksi_mcb')
                            ->select('tanggal','kwh')->where('blok_id', $dBlok->id)->whereMonth('tanggal', '=', $request->bulan)->groupBy('tanggal','kwh')->get();

                    $mcb = DB::table('transaksi_mcb')
                        ->select('kwh')
                        ->where('blok_id', $dBlok->id)
                        ->where('tanggal', $dDate->tanggal)
                        ->orderBy('tanggal', 'ASC')
                        // ->groupBy('tanggal','kwh')
                        // ->whereMonth('tanggal', '=', $request->bulan)
                        ->get();
                    $jmlPerBlok = 0;
                    foreach($mcb as $dMcb){
                        $jmlPerBlok = $jmlPerBlok + $dMcb->kwh;
                    }
                    $dBlok->kwh = $jmlPerBlok;
                    
                    $ppp = $jmlPerBlok;
                    $jmlSemua = $jmlSemua + $ppp;
                    // dd($num);
                    // $dBlok->kwh = $mcb;
                }
                
                // $dDate->xxx = $num;
                $ctrTanggal++;
                $dDate->total = $jmlSemua;
                $dDate->blok = $blok;
            }
        }
        
        // $building = new Gedung();
        // $gedung   = $building->select('id', 'nama_gedung')->get();
        // foreach ($gedung as $dGedung) {
        //     $dataDate = DB::table('transaksi_mcb')
        //         ->select('tanggal')
        //         ->groupBy('tanggal')
        //         ->orderBy('tanggal', 'ASC')
        //         ->whereMonth('tanggal', '=', $request->bulan)
        //         ->get();

        //     $dGedung->tgl = $dataDate;
        //     foreach ($dataDate as $dDate) {
        //         $temp = 0;
        //         $n = 0;
        //         $blok = DB::table('blok')
        //             ->select('id', 'nama_blok')
        //             ->where('gedung_id', $dGedung->id)
        //             ->get();
        //         foreach ($blok as $dBlok) {
        //             $mcb = DB::table('transaksi_mcb')
        //                 ->select('kwh')
        //                 ->where('blok_id', $dBlok->id)
        //                 ->orderBy('tanggal', 'DESC')
        //                 ->orderBy('waktu', 'DESC')
        //                 ->first();
        //             //$dBlok->ep = $mcb->ep;
        //             $dBlok->kwh = isset($mcb->kwh) ? $mcb->kwh : 0;
        //             $n = isset($mcb->kwh) ? $mcb->kwh : 0;
        //             $temp = $temp + $n;
        //         }
        //         $dDate->total = $temp;
        //         $dDate->blok = $blok;
        //     }
        // }

        $xx = array(
            'status'  => 'success',
            'message' => 'Data Berhasil Ditampilkan',
            'data'    => $gedung,
        );
        return response()->json($xx, 200);
    }
    // public function total()
    // {
    //     $building = new Gedung();
    //     $gedung = $building->select('id','nama_gedung')->get();
    //     // $gedung = DB::table('gedung')
    //     //             ->select('id')
    //     //             ->get();
    //     $blok = DB::table('blok')
    //                 ->select('id')
    //                 ->get();
    //     foreach($gedung as $dGedung){
    //         $dataDate  = DB::table('transaksi_mcb')
    //                 ->select('tanggal')
    //                 ->groupBy('tanggal')
    //                 ->orderBy('tanggal', 'DESC')
    //                 ->get();

    public function view()
    {
        $building = new Gedung();
        $blok     = new Blok();
        $response = $building->select('id', 'nama_gedung', 'deskripsi')->get();
        $index    = 1;
        $xx       = 0;
        $tgl      = 0;
        foreach ($response as $data) {
            $comments = Blok::select('id', 'nama_blok', 'deskripsi')->where('gedung_id', $data->id)->orderBy('id', 'DESC')->get();
            $xx       = 0;
            $yy       = 0;
            foreach ($comments as $datamcb) {
                $kwhTerakhir         = TransaksiMcb::select('kwh')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                $lastupdate          = TransaksiMcb::select('created_at')->orderBy('created_at', 'DESC')->orderBy('waktu', 'DESC')->first();
                $paramWarna          = TransaksiMcb::select('kwh')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                $datamcb->kwh        = $kwhTerakhir['ep'];
                $datamcb->paramWarna = $paramWarna['kwh'];
                // $datamcb->lastUpdateTgl = $lastupdate;
                if ($lastupdate != null) {
                    $tgl = $lastupdate;
                }

                // $datamcb->lastUpdateJam = $lastupdate['waktu'];
                $xx = $xx + $kwhTerakhir['kwh'];
            }
            $data->totalKwh      = $xx;
            $data->namablock     = $comments;
            $data->lastUpdateTgl = $tgl;
        }
        return response()->json($response, 200);
    }
    public function detailpopup(Request $request)
    {
        try {
            $transaksi = new TransaksiMcb();
            $blok      = new Blok();
            $response  = $transaksi->select('va', 'vb', 'vc', 'vab',
                'vbc', 'vca', 'ia', 'ib',
                'ic', 'pa', 'pb', 'pc', 'pt',
                'pfa', 'pfb', 'pfc', 'ep', 'eq', 'kwh')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $request->blok_id)->first();
            $kwhSblmTerakhir = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $request->blok_id)->skip(1)->first();
            // if (strlen($kwhSblmTerakhir) > 0) {
            //     $satu                      = $kwhSblmTerakhir->ep;
            //     $dua                       = $response->ep;
            //     $response->totalkwhperblok = $dua - $satu;
            // } else {
            //     $response->totalkwhperblok = "";
            // }
            $response->totalkwhperblok = $kwhSblmTerakhir['ep'];
            $this->status              = true;
        } catch (\Throwable $th) {
            //throw $th;
            $this->status = false;
        }

        if ($this->status) {
            return response()->json(array('code' => 200,
                'status'                             => 'success',
                'message'                            => 'Data Berhasil Ditampilkan',
                'data'                               => $response), 200);
        } else {
            return response()->json(array('code' => 500,
                'status'                             => 'false',
                'message'                            => 'Gagal',
                'data'                               => 'kosong'), 200);
        }

    }

    public function getHistoryMonitoring(Request $request)
    {
        $blokId = $request->get('blokId');

        $data = TransaksiMcb::select('blok_id', 'tanggal', 'waktu',
            'va', 'vb', 'vc', 'vab', 'vbc', 'vca',
            'ia', 'ib', 'ic',
            'pa', 'pb', 'pc', 'pt',
            'pfa', 'pfb', 'pfc',
            'ep', 'eq')
            ->when($blokId, function ($query, $blokId) {
                return $query->where('blok_id', $blokId);
            })
            ->paginate(10);

        return response()->json($data);
    }

    public function getBlock()
    {
        $block = Blok::select('id', 'nama_blok', 'deskripsi')->get();
        return response()->json($block);
    }

    public function tambahBlok(Request $request)
    {
        $block = new Blok();
        $block->gedung_id = $request->get('gedung_id');
        $block->nama_blok = $request->get('nama_blok');
        $block->deskripsi = $request->get('deskripsi');
        $block->save();
        $result = array(
            'code' => 200,
            'status' => true,
            'message' => 'Success'
        );
        return response()->json( $result, $result['code']);
    }

}
