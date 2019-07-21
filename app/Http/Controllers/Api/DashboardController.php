<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
                    a.ep, a.eq, a.kwh,
                    b.nama_blok,
                    c.nama_gedung
            FROM transaksi_mcb a
            JOIN blok b on (a.blok_id = b.id)
            JOIN gedung c on (b.gedung_id = c.id)'
        ));

        return $stats;
    }

    public function history(Request $request)
    {
        $building = new Gedung();
        $blok     = new Blok();
        $response = $building->select('id', 'nama_gedung')->get();
        foreach ($response as $data) {
            $comments = Blok::select('id', 'nama_blok', 'deskripsi')->where('gedung_id', $data->id)->get();
            foreach ($comments as $datamcb) {
                $mcb          = TransaksiMcb::select('ep', 'waktu')->where('blok_id', $datamcb->id)->where('tanggal', $request->tanggal)->get();
                $kwhTerakhir  = TransaksiMcb::select('ep', 'kwh')->orderBy('tanggal', 'DESC')->orderBy('waktu', 'DESC')->where('blok_id', $datamcb->id)->first();
                $datamcb->kwh = $kwhTerakhir->kwh;
                $datamcb->mcb = $mcb;
            }
            $data->blok = $comments;
        }

        return response()->json($response, 200);

    }

}
