<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryMcb;
use App\Models\McbTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class McbTransactionController extends Controller
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
        $mcb_transaction = new McbTransaction();
        $response        = $mcb_transaction->all();

        return $response;
    }

    public function create(Request $request)
    {
        $mcbCategory = new CategoryMcb();
        $response    = array(
            'status'  => false,
            'message' => "Failed to Create Transaction Proses!",
        );

        $mcb_transaction          = new McbTransaction();
        $mcb_transaction->datemcb = isset($request->datemcb) ? $request->datemcb : date('Y-m-d');
        $mcb_transaction->timemcb = isset($request->timemcb) ? $request->timemcb : date('H:i:s');
        $mcb_transaction->stream  = isset($request->stream) ? $request->stream : 0;
        $mcb_transaction->voltage = isset($request->voltage) ? $request->voltage : 0;
        $mcb_transaction->wh      = isset($request->wh) ? $request->wh : 0;

        // jika tidak ada yang ngirim kwh, defaultnya 0
        $kwh                  = isset($request->kwh) ? $request->kwh : 0;
        $mcb_transaction->kwh = $kwh;

        $category = $mcbCategory->where('min', '<=', $kwh)
            ->where('max', '>=', $kwh)
            ->first();

        $mcb_transaction->mcb_id          = $request->mcb_id;
        $mcb_transaction->block_id        = $request->block_id;
        $mcb_transaction->category_mcb_id = $category->id;

        $mcb_transaction->stream_reff  = isset($request->stream_reff) ? $request->stream_reff : 0;
        $mcb_transaction->voltage_reff = isset($request->voltage_reff) ? $request->voltage_reff : 0;

        $mcb_transaction->save();

        return $mcb_transaction;
    }

    public function delete(Request $request)
    {
        $mcb_transaction = new McbTransaction();
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
        $mcb_transaction = new McbTransaction();
        $found           = $mcb_transaction->where('id', $request->id)->first();
        $response        = array(
            'status'  => false,
            'message' => 'Failed to Update Transaction Proses!',
        );

        if ($found) {
            $found->datemcb         = $request->datemcb;
            $found->timemcb         = $request->timemcb;
            $found->current         = $request->current;
            $found->voltage         = $request->voltage;
            $found->wh              = $request->wh;
            $found->kwh             = $request->kwh;
            $found->mcb_id          = $request->mcb_id;
            $found->block_id        = $request->block_id;
            $found->category_mcb_id = $request->category_mcb_id;
            $found->stream_reff     = $request->stream_reff;
            $found->voltage_reff    = $request->voltage_reff;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success to Update Transaction Proses!';
        }

        return $response;

    }

    public function getMcbTransaction()
    {
        $mcbTransaction = new McbTransaction();
        $mcbCategory    = new CategoryMcb();
        $category       = $mcbCategory->get();
        $response       = array();
        foreach ($category as $key => $value) {
            $value['detail'] = $mcbTransaction->where('category_mcb_id', $value['id'])->get();
            $value['total']  = count($value['detail']);
        }

        // $category = $mcbCategory->where('min', '<=', 210)
        //     ->where('max', '>=', 210)
        //     ->first();

        return $category;
    }

    public function generateStatisticData(Request $request)
    {
        $startTime = date('H:i:s');
        $endTime   = date('H:i:s', strtotime('+ 5 minute'));
        $startDate = date('Y-m-d');
        $endDate   = date('Y-m-d');

        if ($request->has('start_time')) {
            $startTime = $request->get('start_time');
        }

        if ($request->has('end_time')) {
            $endTime = $request->get('end_time');
        }

        if ($request->has('start_date')) {
            $startDate = $request->get('start_date');
        }

        if ($request->has('end_date')) {
            $endDate = $request->get('end_date');
        }

        $stats = DB::select(DB::raw(
            'SELECT a.datemcb, a.timemcb, a.kwh, a.voltage, b.mcb_name, c.block_name, d.building_name
            FROM mcb_transaction a
            INNER JOIN mcb b ON a.mcb_id = b.id
            INNER JOIN `block` c ON a.block_id = c.id
            INNER JOIN building d ON c.building_id = d.id
            WHERE a.datemcb BETWEEN :start_date AND :end_date
            AND a.timemcb BETWEEN :start_time AND :end_time'
        ), [
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'start_time' => $startTime,
            'end_time'   => $endTime,
        ]);

        return $stats;

    }

}
