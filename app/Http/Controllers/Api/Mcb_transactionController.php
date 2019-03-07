<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mcb_transaction;
use Illuminate\Http\Request;
use Validator;

class Mcb_transactionController extends Controller
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
        $mcb_transaction = new Mcb_transaction();
        $response        = $mcb_transaction->paginate(1);

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed",
        );

        $validator = Validator::make($request->all(), [
            'datemcb'         => 'required',
            'timemcb'         => 'required',
            'current'         => 'required',
            'voltage'         => 'required',
            'power'           => 'required',
            'mcb_id'          => 'required',
            'block_id'        => 'required',
            'category_mcb_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $mcb_transaction                  = new Mcb_transaction();
        $mcb_transaction->datemcb         = $request->datemcb;
        $mcb_transaction->timemcb         = $request->timemcb;
        $mcb_transaction->current         = $request->current;
        $mcb_transaction->voltage         = $request->voltage;
        $mcb_transaction->power           = $request->power;
        $mcb_transaction->mcb_id          = $request->mcb_id;
        $mcb_transaction->block_id        = $request->block_id;
        $mcb_transaction->category_mcb_id = $request->category_mcb_id;
        $mcb_transaction->save();

        return $mcb_transaction;
    }

    public function delete(Request $request)
    {
        $mcb_transaction = new Mcb_transaction();
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
        $mcb_transaction = new Mcb_transaction();
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
            $found->power           = $request->power;
            $found->mcb_id          = $request->mcb_id;
            $found->block_id        = $request->block_id;
            $found->category_mcb_id = $request->category_mcb_id;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success Update';
        }

        return $response;

    }

    //
}
