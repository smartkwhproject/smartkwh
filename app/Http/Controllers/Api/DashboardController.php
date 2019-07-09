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
                    a.ep, a.eq,
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
        $startDate = date('Y-m-d');

        if ($request->has('tanggal')) {
            $startDate = $request->get('tanggal');
        }

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
            JOIN gedung c on (b.gedung_id = c.id)
            WHERE a.tanggal = ?'
        ), [$startDate]);

        return $stats;
    }

}
