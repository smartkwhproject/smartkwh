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
        $response = $building->select('id', 'nama_gedung')->get();
        $counting = 0;
        foreach ($response as $data) {
            $comments = Blok::select('id', 'nama_blok', 'deskripsi')->where('gedung_id', $data->id)->get();
            foreach ($comments as $datamcb) {
                $kwhTerakhir = TransaksiMcb::select('ep')
                    ->orderBy('tanggal', 'DESC')
                    ->orderBy('waktu', 'DESC')
                    ->where('blok_id', $datamcb->id)
                    ->first();
                $datamcb->kwh = isset($kwhTerakhir->ep) ? $kwhTerakhir->ep : 0;
            }
            $data->pages = $request->page;
            $data->blok  = $comments;
        }
        return response()->json($response, 200);
    }

    public function filterHistory(Request $request)
    {
        $building = new Gedung();
        $gedung   = $building->select('id', 'nama_gedung')
            ->where('id', $request->gedung_id)
            ->first();

        $response = collect([
            'id'          => $gedung->id,
            'nama_gedung' => $gedung->nama_gedung,
            'blok'        => [],
        ]);

        $comments = Blok::select('id', 'nama_blok', 'deskripsi')
            ->where('gedung_id', $request->gedung_id)
            ->get();

        $block = collect([]);

        foreach ($comments as $datamcb) {
            $mcb = TransaksiMcb::select('ep', 'waktu')
                ->where('blok_id', $datamcb->id)
                ->where('tanggal', $request->tanggal)
                ->get();

            $ctr = count($mcb);

            for ($i = 0; $i < $ctr; $i++) {
                if ($i == 0) {
                    $mcb[$i]['kwh'] = $mcb[$i]['ep'];
                } else {
                    $mcb[$i]['kwh'] = abs($mcb[$i]['ep'] - $mcb[$i - 1]['ep']);
                }
            }

            $datamcb->mcb = $mcb;
            $block->push($datamcb);
        }

        $response->put('blok', $block);

        return response()->json($response, 200);
    }

    public function filterTotal(Request $request)
    {
        $building = new Gedung();
        $gedung   = $building->select('id', 'nama_gedung')
            ->where('id', $request->gedung_id)
            ->first();

        $response = collect([
            'id'          => $gedung->id,
            'nama_gedung' => $gedung->nama_gedung,
            'blok'        => [],
        ]);

        $comments = Blok::select('id', 'nama_blok', 'deskripsi')
            ->where('gedung_id', $request->gedung_id)
            ->get();

        $block = collect([]);

        foreach ($comments as $datamcb) {
            $mcb = TransaksiMcb::select('ep', 'waktu')
                ->where('blok_id', $datamcb->id)
                ->whereRaw("MONTH(tanggal) = $request->bulan")
                ->get();

            $ctr      = count($mcb);
            $kwhTotal = 0;

            for ($i = 0; $i < $ctr; $i++) {
                if ($i == 0) {
                    $mcb[$i]['kwh'] = $mcb[$i]['ep'];
                } else {
                    $mcb[$i]['kwh'] = abs($mcb[$i]['ep'] - $mcb[$i - 1]['ep']);
                }
                $kwhTotal += $mcb[$i]['kwh'];
            }

            $datamcb->kwhTotal = $kwhTotal;
            $block->push($datamcb);
        }

        $response->put('blok', $block);
        return response()->json($response, 200);

    }

    public function totalBackup()
    {

        $building = new Gedung();
        $gedung   = $building->select('id', 'nama_gedung')->get();
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
                    //$dBlok->ep = $mcb->ep;
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
            foreach ($comments as $datamcb) {
                $kwhTerakhir         = TransaksiMcb::select('ep')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                $lastupdate          = TransaksiMcb::select('created_at')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                $paramWarna          = TransaksiMcb::select('kwh')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                $datamcb->kwh        = $kwhTerakhir['ep'];
                $datamcb->paramWarna = $paramWarna['kwh'];
                // $datamcb->lastUpdateTgl = $lastupdate;
                if ($lastupdate != null) {
                    $tgl = $lastupdate;
                }

                // $datamcb->lastUpdateJam = $lastupdate['waktu'];
                $xx = $xx + $kwhTerakhir['ep'];
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

    public function testHistory(Request $request)
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

        $data->appends($request->only(['blokId']));

        return view('history', ['data' => $data]);
    }

}
