<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiMcb;
use Illuminate\Http\Request;

class TransaksiMcbController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view()
    {
        $mcb_transaction = new TransaksiMcb();
        $response        = $mcb_transaction->all();

        return $response;
    }

    public function create(Request $request)
    {
        //$mcbCategory = new CategoryMcb();
        $response = array(
            'status'  => false,
            'message' => "Failed to Create Transaction Proses!",
        );

        $mcb_transaction         = new TransaksiMcb();
        $mcb_transaction->tglmcb = $request->get('tglmcb', date('Y-m-d'));
        $mcb_transaction->jammcb = $request->get('jammcb', date('H:i:s'));
        $mcb_transaction->I1     = $request->get('ia', 0);
        $mcb_transaction->I2     = $request->get('ib', 0);
        $mcb_transaction->I3     = $request->get('ic', 0);
        $mcb_transaction->V1     = $request->get('va', 0);
        $mcb_transaction->V2     = $request->get('vb', 0);
        $mcb_transaction->V3     = $request->get('vc', 0);
        $mcb_transaction->VAB    = $request->get('vab', 0);
        $mcb_transaction->VAC    = $request->get('vac', 0);
        $mcb_transaction->VBC    = $request->get('vbc', 0);
        $mcb_transaction->PF     = $request->get('pfa', 0);

        // todo add column pfa, to pfc
        // WH di mikro = pt
        $mcb_transaction->wh = $request->get('pt', 0);
        // KWH di mikro = ep
        $mcb_transaction->kwh     = $request->get('ep', 0);
        $mcb_transaction->blok_id = $request->get('id');

        $save = $mcb_transaction->save();

        if ($save) {
            $response['status']  = true;
            $response['message'] = 'Success Create Transaction';
        }

        return $response;
    }

    public function delete(Request $request)
    {
        $mcb_transaction = new TransaksiMcb();
        $found           = $mcb_transaction->where('id', $request->id)->first();

        if ($found) {
            $found->delete();
        }

        $response = array(
            'status'  => true,
            'message' => 'Success to Delete Transaction Proses!',
        );

        return $response;
    }

    public function update(Request $request)
    {
        $mcb_transaction = new TransaksiMcb();
        $found           = $mcb_transaction->where('id', $request->id)->first();
        $response        = array(
            'status'  => false,
            'message' => 'Failed to Update Transaction Proses!',
        );

        if ($found) {
            $found->tglmcb  = $request->tglmcb;
            $found->jammcb  = $request->jammcb;
            $found->I1      = $request->I1;
            $found->I2      = $request->I2;
            $found->I3      = $request->I3;
            $found->V1      = $request->V1;
            $found->V2      = $request->V2;
            $found->V3      = $request->V3;
            $found->VAB     = $request->VAB;
            $found->VAC     = $request->VAC;
            $found->VBC     = $request->VBC;
            $found->PF      = $request->PF;
            $found->wh      = $request->wh;
            $found->kwh     = $request->kwh;
            $found->blok_id = $request->blok_id;

            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success to Update Transaction Proses!';
        }

        return $response;

    }

    // public function caracteristic()
    // {
    //     $mcbTransaction = new TransaksiMcb();
    //     $response       = array();
    //     foreach ($category as $key => $value) {
    //         $value['detail'] = $mcbTransaction->where('category_mcb_id', $value['id'])->get();
    //         $value['total']  = count($value['detail']);
    //     }

    //     // $category = $mcbCategory->where('min', '<=', 210)
    //     //     ->where('max', '>=', 210)
    //     //     ->first();

    //     return $category;
    // }

    // public function generateStatisticData(Request $request)
    // {
    //     $startTime = date('H:i:s');
    //     $endTime   = date('H:i:s', strtotime('+ 1 hour'));
    //     $startDate = date('Y-m-d');
    //     $endDate   = date('Y-m-d');

    //     if ($request->has('start_time')) {
    //         $startTime = $request->get('start_time');
    //     }

    //     if ($request->has('end_time')) {
    //         $endTime = $request->get('end_time');
    //     }

    //     if ($request->has('start_date')) {
    //         $startDate = $request->get('start_date');
    //     }

    //     if ($request->has('end_date')) {
    //         $endDate = $request->get('end_date');
    //     }

    //     $stats = DB::select(DB::raw(
    //         'SELECT a.datemcb, a.timemcb, a.voltage, a.kwh, b.mcb_name, c.block_name, d.building_name
    //         FROM mcb_transaction a
    //         INNER JOIN mcb b ON a.mcb_id = b.id
    //         INNER JOIN `block` c ON a.block_id = c.id
    //         INNER JOIN building d ON c.building_id = d.id
    //         WHERE a.datemcb BETWEEN :`start_date` AND :end_date
    //         AND a.timemcb BETWEEN :start_time AND :end_time'
    //     ), [
    //         'start_date' => $startDate,
    //         'end_date'   => $endDate,
    //         'start_time' => $startTime,
    //         'end_time'   => $endTime,
    //     ]);

    //     return $stats;

    // }

}
