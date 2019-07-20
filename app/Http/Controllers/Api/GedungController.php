<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Validator;

class GedungController extends Controller
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
        $building = new Gedung();
        $response = $building->all();

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed to Create a Building!",
        );

        $validator = Validator::make($request->all(), [
            'nama_gedung' => 'required',
            'deskripsi'   => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $building              = new Gedung();
        $building->nama_gedung = $request->nama_gedung;
        $building->deskripsi   = $request->deskripsi;
        $building->save();

        return $building;
    }

    public function delete(Request $request)
    {
        $building = new Gedung();
        $found    = $building->where('id', $request->id)->first();

        if ($found) {
            $found->delete();
        }

        $response = array(
            'status'  => true,
            'message' => 'Success to Delete a Building!',
        );

        return $response;
    }

    public function update(Request $request)
    {
        $building = new Gedung();
        $found    = $building->where('id', $request->id)->first();
        $response = array(
            'status'  => false,
            'message' => 'Failed to Update a Building!',
        );

        if ($found) {
            $found->nama_gedung = $request->nama_gedung;
            $found->deskripsi   = $request->deskripsi;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success to Update a Building';
        }

        return $response;

    }

    //
}
