<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryMcb;
use App\Models\McbTransaction;
use Illuminate\Http\Request;

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
        $response        = $mcb_transaction->paginate(1);

        return $response;
    }

    public function create(Request $request)
    {
        $mcbCategory = new CategoryMcb();
        $response    = array(
            'status'  => false,
            'message' => "Failed",
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
            'message' => 'Success Delete',
        );

        return $response;
    }

    public function update(Request $request)
    {
        $mcb_transaction = new McbTransaction();
        $found           = $mcb_transaction->where('id', $request->id)->first();
        $response        = array(
            'status'  => false,
            'message' => 'Failed Update',
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
            $response['message'] = 'Success Update';
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

}
