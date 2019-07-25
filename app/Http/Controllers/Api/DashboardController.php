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
    public function history(Request $request)
    {
        $building = new Gedung();
        $blok     = new Blok();
        $response = $building->select('id', 'nama_gedung')->get();
        foreach ($response as $data) {
            $comments = Blok::select('id', 'nama_blok', 'deskripsi')->where('gedung_id', $data->id)->get();
            foreach ($comments as $datamcb) {
                $mcb             = TransaksiMcb::select('ep', 'waktu')->where('blok_id', $datamcb->id)->where('tanggal', $request->tanggal)->get();
                $kwhTerakhir     = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                $kwhSblmTerakhir = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->skip(1)->first();
                //ep non object
                //$datamcb->kwh    = $kwhTerakhir->ep;
                $datamcb->kwh = isset($kwhTerakhir->ep) ? $kwhTerakhir->ep : 0;
                //$dBlok->ep = isset($mcb->ep) ? $mcb->ep : 0;
                $datamcb->mcb = $mcb;
            }
            $data->blok = $comments;
        }
        return response()->json($response, 200);
    }
    public function filterHistory(Request $request)
    {
        $building = new Gedung();
        $blok     = new Blok();
        $response = $building->select('id', 'nama_gedung')->where('id', $request->gedung_id)->get();
        foreach ($response as $data) {
            $comments = Blok::select('id', 'nama_blok', 'deskripsi')->where('gedung_id', $data->id)->get();
            foreach ($comments as $datamcb) {
                $mcb             = TransaksiMcb::select('ep', 'waktu')->where('blok_id', $datamcb->id)->where('tanggal', $request->tanggal)->get();
                $kwhTerakhir     = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                $kwhSblmTerakhir = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->skip(1)->first();
                $datamcb->kwh    = $kwhTerakhir->ep;
                $datamcb->mcb    = $mcb;
            }
            $data->blok = $comments;
        }
        return response()->json($response, 200);
    }
    public function total()
    {
        $building = new Gedung();
        $gedung   = $building->select('id', 'nama_gedung')->get();
        // $gedung = DB::table('gedung')
        //             ->select('id')
        //             ->get();
        // $blok = DB::table('blok')
        //     ->select('id')
        //     ->get();
        foreach ($gedung as $dGedung) {
            $dataDate = DB::table('transaksi_mcb')
                ->select('tanggal')
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'DESC')
                ->get();

            $dGedung->tgl = $dataDate;
            foreach ($dataDate as $dDate) {
                $blok = DB::table('blok')
                    ->select('id', 'nama_blok')
                    ->where('gedung_id', $dGedung->id)
                    ->get();

                foreach ($blok as $dBlok) {
                    $mcb = DB::table('transaksi_mcb')
                        ->select('ep')
                        ->where('blok_id', $dBlok->id)
                        ->orderBy('tanggal', 'DESC')
                        ->orderBy('waktu', 'DESC')
                        ->first();
                    $dBlok->ep = isset($mcb->ep) ? $mcb->ep : 0;

                }
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

     public function view()
    {
        $building = new Gedung();
        $blok = new Blok();
        $response = $building->select('id','nama_gedung','deskripsi')->get();
        $index = 1;
        foreach($response as $data){
            $comments = Blok::select('id','nama_blok','deskripsi')->where('gedung_id', $data->id)->get();
            foreach($comments as $datamcb){
                // $mcb = TransaksiMcb::select('blok_id','va','vb','vc','vab',
                //                             'vbc','vca','ia','ib',
                //                             'ic','pa','pb','pc','pt',
                //                             'pfa','pfb','pfc','ep','eq','tanggal','waktu')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                $kwhTerakhir = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                $kwhSblmTerakhir = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->skip(1)->first();
                $lastupdate = TransaksiMcb::select('tanggal', 'waktu')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                // $datamcb->mcb = $mcb;
                if(strlen($kwhSblmTerakhir) > 0){
                    $satu = $kwhSblmTerakhir->ep;
                    $dua = $kwhTerakhir->ep;
                    $datamcb->kwh = $dua - $satu;
                    $data->totalKWH = $data->totalKWH + $dua - $satu;
                } else {
                    $datamcb->kwh = "";
                    $data->totalKWH = 0;
                }
                $data->lastUpdate = $lastupdate;
            }
            $data->namablock = $comments;
            
        }
        return response()->json($response, 200);
    }
    
    public function detailpopup(Request $request)
    {
        $transaksi = new TransaksiMcb();
        $blok      = new Blok();
        $response  = $transaksi->select('va', 'vb', 'vc', 'vab',
            'vbc', 'vca', 'ia', 'ib',
            'ic', 'pa', 'pb', 'pc', 'pt',
            'pfa', 'pfb', 'pfc', 'ep', 'eq')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $request->blok_id)->first();
        $kwhSblmTerakhir = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $request->blok_id)->skip(1)->first();
        if (strlen($kwhSblmTerakhir) > 0) {
            $satu                      = $kwhSblmTerakhir->ep;
            $dua                       = $response->ep;
            $response->totalkwhperblok = $dua - $satu;
        } else {
            $response->totalkwhperblok = "";
        }
        return response()->json(array('code' => 200,
            'status'                             => 'success',
            'message'                            => 'Data Berhasil Ditampilkan',
            'data'                               => $response), 200);
    }
}
