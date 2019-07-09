<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function view(Request $request)
    {

        $stats = DB::select(DB::raw(
            'SELECT a.tglmcb, a.jammcb,
                    a.I1, a.I2, a.I3, a.V1, a.V2, a.V3, a.VAB, a.VAC, a.VBC,
                    a.PF, a.wh, a.kwh,
                    b.nama_blok,
                    c.nama_gedung
            FROM transaksi_mcb a
            JOIN blok b on (a.blok_id = b.id)
            JOIN gedung c on (b.gedung_id = c.id)'
        ));

        return $stats;
    }

}
