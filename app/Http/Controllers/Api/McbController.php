<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mcb;
use Illuminate\Http\Request;
use Validator;

class McbController extends Controller
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
        $mcb      = new Mcb();
        $response = $mcb->paginate(1);

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed to Create MCB!",
        );

        $validator = Validator::make($request->all(), [
            'mcb_name'             => 'required',
            'specification_mcb_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $mcb                       = new Mcb();
        $mcb->mcb_name             = $request->mcb_name;
        $mcb->specification_mcb_id = $request->specification_mcb_id;
        $mcb->save();

        return $mcb;
    }

    public function delete(Request $request)
    {
        $mcb   = new Mcb();
        $found = $mcb->where('id', $request->id)->first();

        if ($found) {
            $found->delete();
        }

        $response = array(
            'status'  => true,
            'message' => 'Success to Delete MCB!',
        );

        return $response;
    }

    public function update(Request $request)
    {
        $mcb      = new Mcb();
        $found    = $mcb->where('id', $request->id)->first();
        $response = array(
            'status'  => false,
            'message' => 'Failed to Update MCB!',
        );

        if ($found) {
            $found->mcb_name             = $request->mcb_name;
            $found->specification_mcb_id = $request->specification_mcb_id;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success to Update MCB!';
        }

        return $response;

    }

    //
}
