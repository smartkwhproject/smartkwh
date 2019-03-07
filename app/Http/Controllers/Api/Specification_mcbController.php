<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specification_mcb;
use Illuminate\Http\Request;
use Validator;

class Specification_mcbController extends Controller
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
        $specification_mcb = new Specification_mcb();
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
            'colour'        => 'required',
            'weight'        => 'required',
            'healty_status' => 'required',
            'max_voltage'   => 'required',
            'power_factor'  => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $specification_mcb                = new Specification_mcb();
        $specification_mcb->colour        = $request->colour;
        $specification_mcb->weight        = $request->weight;
        $specification_mcb->healty_status = $request->healty_status;
        $specification_mcb->max_voltage   = $request->max_voltage;
        $specification_mcb->power_factor  = $request->power_factor;
        $specification_mcb->save();

        return $specification_mcb;
    }

    public function delete(Request $request)
    {
        $specification_mcb = new Specification_mcb();
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
        $specification_mcb = new Specification_mcb();
        $found             = $specification_mcb->where('id', $request->id)->first();
        $response          = array(
            'status'  => false,
            'message' => 'Failed Update',
        );

        if ($found) {
            $found->colour        = $request->colour;
            $found->weight        = $request->weight;
            $found->healty_status = $request->healty_status;
            $found->max_voltage   = $request->max_voltage;
            $found->power_factor  = $request->power_factor;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success Update';
        }

        return $response;

    }

    //
}
