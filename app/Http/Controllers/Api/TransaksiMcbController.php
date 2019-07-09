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

        $mcb_transaction          = new TransaksiMcb();
        $mcb_transaction->tglmcb  = isset($request->tglmcb) ? $request->tglmcb : date('Y-m-d');
        $mcb_transaction->jammcb  = isset($request->jammcb) ? $request->jammcb : date('H:i:s');
        $mcb_transaction->I1      = isset($request->I1) ? $request->I1 : 0;
        $mcb_transaction->I2      = isset($request->I2) ? $request->I2 : 0;
        $mcb_transaction->I3      = isset($request->I3) ? $request->I3 : 0;
        $mcb_transaction->V1      = isset($request->V1) ? $request->V1 : 0;
        $mcb_transaction->V2      = isset($request->V2) ? $request->V2 : 0;
        $mcb_transaction->V3      = isset($request->V3) ? $request->V3 : 0;
        $mcb_transaction->VAB     = isset($request->VAB) ? $request->VAB : 0;
        $mcb_transaction->VAC     = isset($request->VAC) ? $request->VAC : 0;
        $mcb_transaction->VBC     = isset($request->VBC) ? $request->VBC : 0;
        $mcb_transaction->PF      = isset($request->PF) ? $request->PF : 0;
        $mcb_transaction->wh      = isset($request->wh) ? $request->wh : 0;
        $mcb_transaction->kwh     = isset($request->kwh) ? $request->kwh : 0;
        $mcb_transaction->blok_id = $request->blok_id;

        $mcb_transaction->save();

        return $mcb_transaction;
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
