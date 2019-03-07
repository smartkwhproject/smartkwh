<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use Validator;

class BuildingController extends Controller
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
        $building = new Building();
        $response = $building->paginate(1);

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed",
        );

        $validator = Validator::make($request->all(), [
            'building_name' => 'required',
            'description'   => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $building                = new Building();
        $building->building_name = $request->building_name;
        $building->description   = $request->description;
        $building->save();

        return $building;
    }

    public function delete(Request $request)
    {
        $building = new Building();
        $found    = $building->where('id', $request->id)->first();

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
        $building = new Building();
        $found    = $building->where('id', $request->id)->first();
        $response = array(
            'status'  => false,
            'message' => 'Failed Update',
        );

        if ($found) {
            $found->building_name = $request->building_name;
            $found->description   = $request->description;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success Update';
        }

        return $response;

    }

    //
}
