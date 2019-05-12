<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SpecificationMcb;
use Illuminate\Http\Request;
use Validator;

class SpecificationMcbController extends Controller
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
        $specification_mcb = new SpecificationMcb();
        $response          = $specification_mcb->paginate(1);

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed",
        );

        $validator = Validator::make($request->all(), [
            'colour'      => 'required',
            'max_stream'  => 'required',
            'max_voltage' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $specification_mcb              = new SpecificationMcb();
        $specification_mcb->colour      = $request->colour;
        $specification_mcb->max_stream  = $request->max_stream;
        $specification_mcb->max_voltage = $request->max_voltage;
        $specification_mcb->save();

        return $specification_mcb;
    }

    public function delete(Request $request)
    {
        $specification_mcb = new SpecificationMcb();
        $found             = $specification_mcb->where('id', $request->id)->first();

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
        $specification_mcb = new SpecificationMcb();
        $found             = $specification_mcb->where('id', $request->id)->first();
        $response          = array(
            'status'  => false,
            'message' => 'Failed Update',
        );

        if ($found) {
            $found->colour      = $request->colour;
            $found->max_stream  = $request->max_stream;
            $found->max_voltage = $request->max_voltage;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success Update';
        }

        return $response;

    }

    //
}
